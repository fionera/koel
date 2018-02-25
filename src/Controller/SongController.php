<?php

namespace App\Controller;

use App\Entity\Song;
use App\Repository\SongRepository;
use App\Service\SongService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SongController
 * @package App\Controller
 * @Route("/song", name="song_")
 */
class SongController extends Controller
{
    /**
     * @Route("", name="base")
     */
    public function base()
    {
        return $this->forward('App\Controller\SongController::index');
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->json([
            'message' => 'No Song provided'
        ]);
    }

    /**
     * @Route("/{songID}/play", name="song")
     * @param string $songID
     * @param SongService $songService
     * @param SongRepository $songRepository
     * @return Response
     * @throws \InvalidArgumentException
     */
    public function playSong(string $songID, SongService $songService, SongRepository $songRepository): Response
    {
        $song = $songRepository->findOneBy(['id' => $songID]);

        if ($song === null) {
            return $this->json([
                'message' => 'Could not find Song: ' . $songID
            ]);
        }

        return new Response($songService->readSong($song), 200, [
            'Content-Length' => $songService->getSongSize($song)
        ]);
    }
}
