<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="laravel_api_")
 */
class LaravelAPIController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTToken;

    /**
     * LaravelAPIController constructor.
     * @param EntityManagerInterface $entityManager
     * @param JWTTokenManagerInterface $JWTToken
     */
    public function __construct(EntityManagerInterface $entityManager, JWTTokenManagerInterface $JWTToken)
    {
        $this->entityManager = $entityManager;
        $this->JWTToken = $JWTToken;
    }

    /**
     * @Route("/me", name="me", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function loginAction(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if ($email === null || $email === '' || $password === null || $password === '') {
            return $this->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $userRepo = $this->entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => $email]);

        if ($user === null) {
            return $this->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return $this->json([
                'token' => $this->JWTToken->create($user)
            ]);
    }
}
