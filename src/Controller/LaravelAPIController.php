<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * LaravelAPIController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/me", name="me", )
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function meAction(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if ($email === null || $email === '' || $password === null || $password === '') {
            return $this->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $userRepo = $this->entityManager->getRepository(User::class);

        //$userRepo->findOneBy([''])

        return null;
    }
}
