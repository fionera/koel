<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
}
