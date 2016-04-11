<?php
namespace Connections\ConnectionsBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Connections\ConnectionsBundle\Helper\FieldsValidator;
use Symfony\Component\HttpFoundation\Response;


class RegistrationController extends FOSRestController
{

    /**
     * Create new user
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * POST Route annotation.
     * @Post("/api/register")
     */
    public function newUserAction()
    {
        if (!$this->get('request')->request->all()) {
            return $this->responseMessage("No data");
        }
        if($this->formErrors()){
            return $this->responseMessage($this->formErrors());
        }
        $userRepository = $this->getDoctrine()->getRepository('ConnectionsBundle:User');
        $user = $userRepository->createUser($this->get('request')->request->all());
        return $this->responseMessage($user["message"]);
    }

    private function formErrors()
    {
        $validator = new FieldsValidator();
        return $validator->validateRegistrationForm($this->get('request')->request->all());
    }

    private function responseMessage($message)
    {
        return new Response(
            $this->render(
                'ConnectionsBundle::login.html.twig',
                array(
                    'error' => $message
                ))->getContent(),
            400
        );
    }


}