<?php

namespace App\Controller;

use App\Entity\Interaction;
use App\Entity\Song;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Tests\Compiler\J;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/interaction", name="interaction_")
 */
class InteractionController extends Controller
{
    /**
     * @Route("/{song}/play", name="play")
     * @param string $song
     * @param EntityManagerInterface $entityManager
     * @param UserInterface|null $authenticatedUser
     * @return JsonResponse
     */
    public function play(string $song, EntityManagerInterface $entityManager, UserInterface $authenticatedUser = null)
    {
        if ($authenticatedUser === null || !$authenticatedUser instanceof User) {
            return new JsonResponse(null, 401);
        }

        $songEntity = $entityManager->getRepository(Song::class)->findOneBy(['id' => $song]);

        if ($songEntity === null) {
            return new JsonResponse(null, 404);
        }

        $interaction = $entityManager->getRepository(Interaction::class);

        if ($interaction === null) {
            $interaction = new Interaction($authenticatedUser, $songEntity);
        }

        $interaction->setPlayCount($interaction->getPlayCount() + 1);

        $entityManager->persist($interaction);
        $entityManager->flush();

        return new JsonResponse([
           'song' => $songEntity->getId(),
           'user' => $authenticatedUser->getId(),
           'playCount' => $interaction->getPlayCount()
        ]);
    }
}
