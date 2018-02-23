<?php

namespace App\Controller;

use App\Entity\Song;
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
     * @Route("/{songID}", name="song")
     * @param string $songID
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function song(string $songID)
    {
        $music = $this->container->get('League\Flysystem\Filesystem');
        $song = $this->getDoctrine()->getRepository(Song::class)->findOneBy(['id' => $songID]);

        if ($song === null) {
            return $this->json([
                'message' => 'Could not find Song: ' . $songID
            ]);
        }

        return $this->file($music->get($song->getPath())->read(), basename($song->getPath()));
    }

}
