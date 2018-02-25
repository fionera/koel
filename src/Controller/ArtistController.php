<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ArtistController
 * @package App\Controller
 * @Route("/artist", name="artist_")
 */
class ArtistController extends Controller
{
    /**
     * @Route("", name="base")
     */
    public function base()
    {
        return $this->forward('App\Controller\ArtistController::index');
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->json([
            'message' => 'No Artist provided'
        ]);
    }

    /**
     * @Route("/{artistID}", name="artist")
     */
    public function artist($artistID)
    {
        return $this->json([
            'message' => 'Could not find Artist: ' . $artistID
        ]);
    }
}
