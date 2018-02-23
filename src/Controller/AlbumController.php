<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AlbumController
 * @package App\Controller
 * @Route("/album", name="album_")
 */
class AlbumController extends Controller
{
    /**
     * @Route("", name="base")
     */
    public function base()
    {
        return $this->forward('App\Controller\AlbumController::index');
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->json([
            'message' => 'No Album provided'
        ]);
    }

    /**
     * @Route("/{albumID}", name="album")
     */
    public function album($albumID)
    {
        return $this->json([
            'message' => 'Could not find Album: ' . $albumID
        ]);
    }
}
