<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
