<?php
namespace Connections\ConnectionsBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;


class ProfileController extends FOSRestController
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
     * Get user details
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Get("/user-details/{id}")
     */
    public function getUserProfileAction($id)
    {
        $userId = $id > 0 ? $id : $this->user;
        $userRepository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');
        $userDetails = $userRepository->getDetails($userId);
        if($userDetails){
            $view = $this->view($userDetails, 200);
            return $this->handleView($view);
        }
    }

    /**
     * Get user direct friends
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Post("/user-direct-friends/{id}")
     */
    public function getUserDirectFriendsAction($id, Request $request)
    {
        $page = json_decode($request->getContent(), true)["Page"];
        $userId = $id > 0 ? $id : $this->user;
        $userRepository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');
        $directFriends = $userRepository->getDirectFriends($userId, (int)(($page-1)*10) );
        $view = $this->view($directFriends, 200);
        return $this->handleView($view);

    }

    /**
     * Get user friends of friends ( followings )
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Post("/user-fof/{id}")
     */
    public function getUserFriendsOfFriendsAction($id, Request $request)
    {
        $page = json_decode($request->getContent(), true)["Page"];
        $userId = $id > 0 ? $id : $this->user;
        $userRepository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');
        $friendsOfFriends = $userRepository->getFriendsOfFriends($userId, (int)(($page-1)*10) );
        $view = $this->view($friendsOfFriends, 200);
        return $this->handleView($view);

    }

    /**
     * Get user suggested friends
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Post("/user-suggested/{id}")
     */
    public function getUserSuggestedFriendsAction($id, Request $request)
    {
        $page = json_decode($request->getContent(), true)["Page"];
        $userId = $id > 0 ? $id : $this->user;
        $userRepository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');
        $suggestedFriends = $userRepository->getSuggestedFriends($userId, (int)(($page-1)*10) );
        $view = $this->view($suggestedFriends, 200);
        return $this->handleView($view);

    }


}