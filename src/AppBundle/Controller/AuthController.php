<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends Controller
{
  /**
   * @Route("/login", name="login")
   */
  public function loginAction(Request $request)
  {
    $user = $this->getUser();

    if ($user instanceof UserInterface) {
      return $this->redirectToRoute('homepage');
    }

    /** @var AuthenticationException $exception */
    $exception = $this->get('security.authentication_utils')
      ->getLastAuthenticationError();

    return $this->render('auth/login.html.twig', [
      'error' => $exception ? $exception->getMessage() : NULL,
    ]);
  }

  /**
   * @Route("/logout", name="logout")
   */
  public function logoutAction(Request $request)
  {
    $url = $this->get("router")->generate("homepage");

    return new RedirectResponse($url);
  }
}
