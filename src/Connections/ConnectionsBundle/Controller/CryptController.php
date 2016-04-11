<?php

namespace Connections\ConnectionsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CryptController extends Controller{

    public function cryptAction(){

        $repository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');
        $users = $repository->findAll();

        foreach($users as $user){
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $encodedPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($encodedPassword);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        };
    }

}