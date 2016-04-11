<?php

namespace Connections\ConnectionsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request){

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $errorDetail = null;
        if($error){
            $errorDetail = "Wrong username / password";
        }

        return new Response(
            $this->render(
                'ConnectionsBundle::login.html.twig',
                array(
                    'last_username' => $lastUsername,
                    'error'         => $errorDetail,
                ))->getContent(),
            203
        );

    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction(){

    }

    /**
     * @Route("/logout",name="logout")
     */
    public function logoutAction(){

    }


}