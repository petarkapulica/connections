<?php
namespace Connections\ConnectionsBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;


class ConnectionsController extends FOSRestController
{
    private $user;

    /**
     * Constructor method
     */
    public function __construct()
    {
        $session = new Session();
        $this->user = $session->all()["User"];
    }


    /**
     * Get all users
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Post("/users")
     */
    public function getAllUsersAction(Request $request)
    {
        $jsonData = json_decode($request->getContent(), true);
        $userId = $this->user;
        $userRepository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');
        $users = $userRepository->getAllUsers($userId, $jsonData);
        $view = $this->view($users, 200);
        return $this->handleView($view);
    }

    /**
     * Follow specific user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Post("/follow")
     */
    public function tryToFollowUserAction(Request $request){
        $userToFollowId = (int) json_decode($request->getContent(), true)["Id"];
        $userId = $this->user;
        $userRepository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');

        if(!$this->checkIfUserToFollowOrUnfollowExists($userToFollowId, $userRepository)){
            $view = $this->view(array('error' => 'User does not exist'), 400);
            return $this->handleView($view);
        }
        if($this->checkIfFollow($userToFollowId, $userId, $userRepository)){
            $view = $this->view(array('error' => 'You are already following this user'), 400);
            return $this->handleView($view);
        }
        $this->followUser($userToFollowId, $userId, $userRepository);
        $view = $this->view(array('success' => 'You have successfully made new connection!'), 200);
        return $this->handleView($view);
    }

    /**
     * Unfollow specific user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Delete("/unfollow")
     */
    public function tryToUnfollowUserAction(Request $request){
        $userToUnfollowId = (int) json_decode($request->getContent(), true)["Id"];
        $userId = $this->user;
        $userRepository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');

        if(!$this->checkIfUserToFollowOrUnfollowExists($userToUnfollowId, $userRepository)){
            $view = $this->view(array('error' => 'User does not exist'), 400);
            return $this->handleView($view);
        }
        $this->unfollowUser($userToUnfollowId, $userId, $userRepository);
        $view = $this->view(array('success' => 'You have successfully unfollowed!'), 200);
        return $this->handleView($view);
    }

    private function checkIfUserToFollowOrUnfollowExists($userToFollowOrUnfollowId, $userRepository){
        return $userRepository->checkIfUserExists($userToFollowOrUnfollowId);
    }

    private function checkIfFollow($userToFollowId, $userId, $userRepository){
        return $userRepository->checkIfAlreadyFollowing($userToFollowId, $userId);
    }

    private function followUser($userToFollowId, $userId, $userRepository){
        return $userRepository->followUser($userToFollowId, $userId);
    }

    private function unfollowUser($userToUnfollowId, $userId, $userRepository){
        return $userRepository->unfollowUser($userToUnfollowId, $userId);
    }

}