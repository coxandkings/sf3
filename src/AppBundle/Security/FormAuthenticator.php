<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FormAuthenticator extends AbstractGuardAuthenticator
{
  private $router;
  private $passwordEncoder;

  public function __construct(RouterInterface $router, $passwordEncoder) {
    $this->router = $router;
    $this->passwordEncoder = $passwordEncoder;
  }

  public function getCredentials(Request $request)
  {
    if ($request->getPathInfo() != '/login' || !$request->isMethod('POST')) {
      return;
    }

    return [
      'username' => $request->request->get('username'),
      'password' => $request->request->get('password'),
    ];
  }

  public function getUser($credentials, UserProviderInterface $userProvider)
  {
    try {
      $user = $userProvider->loadUserByUsername($credentials['username']);
      return $user;
    }catch(\Exception $ex)
    {
      throw new CustomUserMessageAuthenticationException("User was not found.");
    }
  }

  public function checkCredentials($credentials, UserInterface $user)
  {
    if ($user->getPassword() === $this->passwordEncoder->encodePassword($user, $credentials['password'])) {
      return true;
    }
    throw new CustomUserMessageAuthenticationException("Password is incorrect.");
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
  {
    $url = $this->router->generate('homepage');
    
    return new RedirectResponse($url);
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
  {
    $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

    $url = $this->router->generate('login');

    return new RedirectResponse($url);
  }

  public function start(Request $request, AuthenticationException $authException = null)
  {
    $url = $this->router->generate('login');

    return new RedirectResponse($url);
  }

  public function supportsRememberMe()
  {
    return true;
  }
}
