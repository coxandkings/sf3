<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsersController extends Controller
{
    /**
     * @Route("/users/{id}", name="api_users_get_user", requirements={"id" = "\d+"})
     */
    public function getUserAction($id)
    {
		return new JsonResponse([]);
    }

    /**
     * @Route("/users/me", name="api_users_me")
     */
    public function meAction()
    {
    	$user = $this->getUser();

    	return new JsonResponse(['username'=>$user->getUsername()]);
    }
}
