<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
    }

    /**
     * @Route("/logout", name="logout")
     * @param TokenStorageInterface $tokenStorage
     * @return JsonResponse
     */
    public function logout(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $anonToken = new AnonymousToken('', 'anon.', array());
        $tokenStorage->setToken($anonToken);

        return new JsonResponse('', 200);
    }
}
