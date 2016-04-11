<?php

namespace Connections\ConnectionsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Connections\ConnectionsBundle\Entity\User;
use Connections\ConnectionsBundle\Entity\Userdetails;


class UserRepository extends EntityRepository{

    private $userId;
    private $userDirectsSQL;
    private $userFollowingsSQL;
    private $users;
    private $isDirect = 0;
    private $idForUpdate;

    public function createUser($data){
        if($this->checkIfAlreadyExist($data["Username"], $data["Email"])){
            return array("message" => "Username or Email already exists");
        }
        $em = $this->getEntityManager();
        try {
            $em->getConnection()->beginTransaction();
            $this->createBasic($data);
            $this->createDetails($data);
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
        }
        return array("message" => "You have successfully registered !!!");
    }


    public function getDetails($id){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare('
              SELECT
                  u.Id, u.Username, u.Email,
                  ud.Name, ud.Address, ud.Company, ud.Blog, ud.Bio, ud.Image,
                  count(distinct ur.RepositoryId) as Repositories,
                   (
                      SELECT COUNT(Followers.FollowerId) AS Followers
                      FROM Followers
                      WHERE Followers.FollowingId = :id

                   ) AS FollowersNumber,
                   (
                      SELECT COUNT(Followers.FollowingId) AS Following
                      FROM Followers
                      WHERE Followers.FollowerId = :id

                   ) AS FollowingNumber
                FROM User as u
                LEFT JOIN UserDetails as ud
                ON u.Id = ud.UserId
                LEFT JOIN UserRepositories as ur
                ON u.Id = ur.UserId
                WHERE u.Id = :id;'
            );
        $params['id'] = $id;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getDirectFriends($id, $page){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
             SELECT
                f.FollowingId AS Id, ud.Name, ud.Image FROM Followers as f
             LEFT JOIN UserDetails as ud
             ON ud.UserId = f.FollowingId
             WHERE f.FollowerId = :id
             AND f.IsDirect = 1
             LIMIT {$page}, 10
             ;"
            );
        $params['id'] = $id;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getFriendsOfFriends($id, $page){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
              SELECT
                f.FollowingId AS Id, ud.Name, ud.Image FROM Followers as f
             LEFT JOIN UserDetails as ud
             ON ud.UserId = f.FollowingId
             WHERE f.FollowerId = :id
             AND f.IsDirect = 0
             LIMIT {$page}, 10

             ;"
            );
        $params['id'] = $id;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getSuggestedFriends($id, $page){
        return $this->getDirects($id)
                    ->getFollowings($id)
                    ->getSuggested($id, $page);
    }

    public function getAllUsers($id, $jsonData){
        return $this->getUsers($jsonData)
                    ->setFlags($id)
                    ->countTotal();
    }

    public function checkIfAlreadyFollowing($userToFollowId, $userId){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
             SELECT
                Id FROM Followers
             WHERE
                FollowerId = :userId
             AND
                FollowingId = :userToFollowId
             ;"
            );
        $params['userId'] = $userId;
        $params['userToFollowId'] = $userToFollowId;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function followUser($userToFollowId, $userId){
        $this->checkIfUserToFollowOrUnfollowIsFollowingUser($userToFollowId, $userId);
        $this->isDirect ?
            $this->followUserAndUpdateStatuses($userToFollowId, $userId) :
            $this->justFollowUser($userToFollowId, $userId);
    }

    public function checkIfUserExists($id){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
              SELECT
                  Id
              FROM
                  User
              WHERE
                  Id = :id
             ;"
            );
        $params['id'] = $id;
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function unfollowUser($userToUnfollowId, $userId){
        $this->checkIfUserToFollowOrUnfollowIsFollowingUser($userToUnfollowId, $userId);
        $this->isDirect ?
            $this->unfollowUserAndUpdateStatuses($userToUnfollowId, $userId) :
            $this->justUnfollowUser($userToUnfollowId, $userId);
    }

    private function createBasic($data){
        $user = new User();
        $user->setUsername($data["Username"]);
        $user->setPassword(password_hash($data["Password"], PASSWORD_BCRYPT, array('cost' => 12)));
        $user->setEmail($data["Email"]);
        $roleRepository = $this->getEntityManager()->getRepository('ConnectionsBundle:Role');
        $role = $roleRepository->findOneBy(array('name' => "ROLE_USER"));
        $user->setRoleid($role);
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
        $this->userId = $user->getId();
    }

    private function createDetails($data){
        $userDetails = new Userdetails();
        $userRepository = $this->getEntityManager()->getRepository('ConnectionsBundle:User');
        $user = $userRepository->findOneBy(array('id' => $this->userId));
        $userDetails->setUserid($user);
        $userDetails->setName($data["Name"]);
        if(isset($data["Address"])){
            $userDetails->setAddress($data["Address"]);
        }
        if(isset($data["Company"])){
            $userDetails->setCompany($data["Company"]);
        }
        if(isset($data["Blog"])){
            $userDetails->setBlog($data["Blog"]);
        }
        if(isset($data["Bio"])){
            $userDetails->setBio($data["Bio"]);
        }
        $userDetails->setImage($data["Image"]);
        $em = $this->getEntityManager();
        $em->persist($userDetails);
        $em->flush();
    }

    private function checkIfAlreadyExist($username, $email){
        $userRepository = $this->getEntityManager()->getRepository('ConnectionsBundle:User');
        if($userRepository->findOneByUsername($username)){
            return true;
        }
        if($userRepository->findOneByEmail($email)){
            return true;
        }
    }

    private function getDirects($id){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
              SELECT
                  FollowingId AS Id FROM Followers
              WHERE FollowerId = :id
              AND IsDirect = 1
             ;"
            );
        $params['id'] = $id;
        $stmt->execute($params);
        $userDirects = $stmt->fetchAll();
        $userDirectsArray = [];
        foreach($userDirects as $key => $value){
            $userDirectsArray[] = $value["Id"];
        }
        $this->userDirectsSQL = implode(", ", $userDirectsArray);
        return $this;
    }

    private function getFollowings($id){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
              SELECT
                  FollowingId AS Id FROM Followers
              WHERE FollowerId = :id
             ;"
            );
        $params['id'] = $id;
        $stmt->execute($params);
        $userFollowings = $stmt->fetchAll();
        $userFollowingsArray = [];
        foreach($userFollowings as $key => $value){
            $userFollowingsArray[] = $value["Id"];
        }
        $this->userFollowingsSQL = implode(", ", $userFollowingsArray);
        return $this;
    }

    private function getSuggested($id, $page){
        if(!$this->userDirectsSQL || !$this->userFollowingsSQL){
            return [];
        }
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
              SELECT
                  f.FollowingId AS Id, ud.Name, ud.Image FROM Followers as f
              LEFT JOIN UserDetails as ud
                  ON ud.UserId = f.FollowingId
              WHERE
                  f.IsDirect = 1
              AND
                  f.FollowerId IN ({$this->userDirectsSQL})
              AND
                  f.FollowingId NOT IN ({$this->userFollowingsSQL})
              AND
                  f.FollowingId != :id
              LIMIT {$page}, 10
             ;"
            );
        $params['id'] = $id;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function getUsers($jsonData){
        $sql = "
              SELECT ud.UserId, ud.Name, ud.Company, ud.Image,
                   (
                      SELECT COUNT(Followers.FollowerId) AS Followers
                      FROM Followers
                      WHERE Followers.FollowingId = ud.UserId
                   ) AS FollowersNumber,
                   (
                      SELECT COUNT(Followers.FollowingId) AS Following
                      FROM Followers
                      WHERE Followers.FollowerId = ud.UserId
                   ) AS FollowingNumber,
                   (
                      SELECT COUNT(UserRepositories.RepositoryId) AS Repositories
                      FROM UserRepositories
                      WHERE UserRepositories.UserId = ud.UserId
                   ) AS RepositoriesNumber
                   FROM UserDetails as ud
             ";
        $page = (int)(($jsonData["Page"]-1)*10);
        $order = $jsonData["Order"];
        if( isset($order) && !empty($order) ){
            switch($order){
                case "By Followers":
                    $sql .= " ORDER BY FollowingNumber DESC";
                    break;
                case "By Followings":
                    $sql .= " ORDER BY FollowersNumber DESC";
                    break;
                case "By Repositories":
                    $sql .= " ORDER BY RepositoriesNumber DESC";
                    break;
            }
        };
        $sql .= " LIMIT {$page}, 10;";
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($sql);
        $stmt->execute();
        $this->users = $stmt->fetchAll();
        return $this;
    }

    private function setFlags($id){
        foreach ($this->users as $key => $value) {
            $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("
              SELECT
                  Id FROM Followers
              WHERE FollowerId = :id
              AND FollowingId = :tmpId
             ;"
                );
            $params['id'] = $id;
            $params['tmpId'] = $value["UserId"];
            $stmt->execute($params);
            $this->users[$key]["IsFollowing"] = !empty($stmt->fetchAll()) ?
                true : false;
        }
        return $this;
    }

    private function countTotal(){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare('
              SELECT COUNT(*) AS Total FROM User;'
            );
        $stmt->execute();
        $total = $stmt->fetchAll();
        $response = array(
            "Users" => $this->users,
            "TotalRecords" => $total[0]["Total"]
        );
        return $response;
    }

    private function checkIfUserToFollowOrUnfollowIsFollowingUser($userToFollowOrUnfollowId, $userId){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
              SELECT
                  Id FROM Followers
              WHERE FollowerId = :userToFollowId
              AND FollowingId = :userId
             ;"
            );
        $params['userToFollowId'] = $userToFollowOrUnfollowId;
        $params['userId'] = $userId;
        $stmt->execute($params);
        $result = $stmt->fetchColumn();
        if($result){
            $this->isDirect = 1;
            $this->idForUpdate = $result;
        }
    }

    private function followUserAndUpdateStatuses($userToFollowId, $userId){
        $em = $this->getEntityManager();
        $id = (int)($this->idForUpdate);
        try {
            $em->getConnection()->beginTransaction();
            $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("
              INSERT INTO Followers (FollowerId, FollowingId, IsDirect)
              VALUES (:userId, :userToFollowId, :isDirect)
             ;"
                );
            $params['userToFollowId'] = $userToFollowId;
            $params['userId'] = $userId;
            $params['isDirect'] = $this->isDirect;
            $stmt->execute($params);

            $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("
              UPDATE Followers
                SET IsDirect = 1
                WHERE Followers.Id = $id
             ;"
                );
            $stmt->execute();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
        }
    }

    private function unfollowUserAndUpdateStatuses($userToUnfollowId, $userId){
        $em = $this->getEntityManager();
        $id = (int)($this->idForUpdate);
        try {
            $em->getConnection()->beginTransaction();
            $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("
              DELETE FROM Followers
              WHERE FollowerId = :userId
              AND FollowingId = :userToUnfollowId
             ;"
                );
            $params['userToUnfollowId'] = $userToUnfollowId;
            $params['userId'] = $userId;
            $stmt->execute($params);

            $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("
                UPDATE Followers
                SET IsDirect = 0
                WHERE Followers.Id = $id
             ;"
                );
            $stmt->execute();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
        }
    }

    private function justFollowUser($userToFollowId, $userId){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
              INSERT INTO Followers (FollowerId, FollowingId, IsDirect)
              VALUES (:userId, :userToFollowId, :isDirect)
             ;"
            );
        $params['userToFollowId'] = $userToFollowId;
        $params['userId'] = $userId;
        $params['isDirect'] = $this->isDirect;
        $stmt->execute($params);
    }

    private function justUnfollowUser($userToUnfollowId, $userId){
        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare("
              DELETE FROM Followers
              WHERE FollowerId = :userId
              AND FollowingId = :userToUnfollowId
             ;"
            );
        $params['userToUnfollowId'] = $userToUnfollowId;
        $params['userId'] = $userId;
        $stmt->execute($params);
    }

}
