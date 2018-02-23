<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PlaylistController
 * @package App\Controller
 * @Route("/playlist", name="playlist_")
 */
class PlaylistController extends Controller
{
    /**
     * @Route("", name="base")
     */
    public function base()
    {
        return $this->forward('App\Controller\PlaylistController::index');
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->json([
            'message' => 'No Playlist provided'
        ]);
    }

    /**
     * @Route("/{playlistID}", name="playlist")
     */
    public function playlist($playlistID)
    {
        return $this->json([
            'message' => 'Could not find Playlist: ' . $playlistID
        ]);
    }
}
