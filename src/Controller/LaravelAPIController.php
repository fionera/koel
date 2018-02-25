<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Interaction;
use App\Entity\Song;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param JWTTokenManagerInterface $JWTToken
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/me", name="me_post", methods={"POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function mePostAction()
    {
        return $this->redirect($this->generateUrl('user_login'), 307);
    }

    /**
     * @Route("/me", name="me_options", methods={"OPTIONS"})
     * @return Response
     */
    public function meOptionsAction()
    {
        return new Response('', 200, ['Allow' => 'GET,HEAD,POST,PUT,DELETE']);
    }

    /**
     * @Route("/me", name="me_delete", methods={"DELETE"})
     * @return Response
     */
    public function meDeleteAction()
    {
        $anonToken = new AnonymousToken('theTokensKey', 'anon.', array());
        $this->get('security.token_storage')->setToken($anonToken);

        return new Response('', 200);
    }

    /**
     * @Route("/interaction/play", name="me_interaction_play", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserInterface|null $authenticatedUser
     * @return Response
     */
    public function interactionPlayAction(Request $request, EntityManagerInterface $entityManager, UserInterface  $authenticatedUser = null): Response
    {
        if ($authenticatedUser === null || !$authenticatedUser instanceof User) {
            return new JsonResponse(null, 401);
        }

        $json_data = json_decode($request->getContent(), true);

        $song = $entityManager->getRepository(Song::class)->findOneBy(['id' => $json_data['song']]);

        if ($song === null) {
            return new JsonResponse(null, 404);
        }

        $interaction = $entityManager->getRepository(Interaction::class)->findOneBy(['songID' => $song, 'userID' => $authenticatedUser]);

        if ($interaction === null) {
            $interaction = new Interaction($authenticatedUser, $song, false, 0);
        }

        $interaction->setPlayCount($interaction->getPlayCount() + 1);

        $entityManager->persist($interaction);
        $entityManager->flush();

        $data = [
            'song_id' => $song->getId(),
            'liked' => $interaction->getLiked(),
            'play_count' => $interaction->getPlayCount(),
            'song' =>
                [
                    'id' => $song->getId(),
                    'album_id' => $song->getAlbumID()->getId(),
                    'artist_id' => $song->getArtistID()->getId(),
                    'disc' => $song->getDisc(),
                    'length' => $song->getLength(),
                    'title' => $song->getTitle(),
                    'track' => $song->getTrack(),
                    'created_at' => $song->getCreatedAt(),
                ],
            'user' =>
                [
                    'id' => $authenticatedUser->getId(),
                    'name' => $authenticatedUser->getName(),
                    'email' => $authenticatedUser->getEmail(),
                    'is_admin' => $authenticatedUser->isAdmin(),
                    'preferences' => []
                ]
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/data", name="data", methods={"GET"})
     * @param UserInterface|null $authenticatedUser
     * @return JsonResponse
     */
    public function dataAction(Request $request, UserInterface $authenticatedUser = null)
    {
        if ($authenticatedUser === null || !$authenticatedUser instanceof User) {
            return new JsonResponse(null, 401);
        }

        $response = [
            'allowDownload' => false,
            'cdnUrl' => $request->getSchemeAndHttpHost() . '/',
            'currentVersion' => 'v3.7.2',
            'latestVersion' => 'v3.7.2',
            'playlists' => [],
            'settings' => [
                'media_path' => getenv('MEDIA_FOLDER')
            ],
            'supportsTranscoding' => true,
            'useLastfm' => false,
            'useYouTube' => false,
            'useiTunes' => false,
        ];

        $response['interactions'] = array_map(function (Interaction $interaction) {
            return [
                'song_id' => $interaction->getSongID()->getId(),
                'liked' => $interaction->getLiked(),
                'play_count' => $interaction->getPlayCount(),
            ];
        }, $this->entityManager->getRepository(Interaction::class)->findBy(['userID' => $authenticatedUser->getId()]));

        $response['albums'] = array_map(function (Album $album) {
            return [
                'id' => $album->getId(),
                'name' => $album->getName(),
                'artist_id' => $album->getArtistID()->getId(),
            ];
        }, $this->entityManager->getRepository(Album::class)->findAll());

        $response['artists'] = array_map(function (Artist $artist) {
            return [
                'id' => $artist->getId(),
                'name' => $artist->getName(),
                'image' => null,
            ];
        }, $this->entityManager->getRepository(Artist::class)->findAll());

        $response['songs'] = array_map(function (Song $song) {
            return [
                'id' => $song->getId(),
                'album_id' => $song->getAlbumID()->getId(),
                'artist_id' => $song->getArtistID()->getId(),
                'disc' => $song->getDisc(),
                'length' => $song->getLength(),
                'title' => $song->getTitle(),
                'track' => $song->getTrack(),
                'created_at' => $song->getCreatedAt(),
            ];
        }, $this->entityManager->getRepository(Song::class)->findAll());

        $response['users'] = array_map(function (User $user) {
            return [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'is_admin' => $user->isAdmin(),
                'preferences' => []
            ];
        }, $this->entityManager->getRepository(User::class)->findAll());

        $response['currentUser'] = [
            'id' => $authenticatedUser->getId(),
            'name' => $authenticatedUser->getName(),
            'email' => $authenticatedUser->getEmail(),
            'is_admin' => $authenticatedUser->isAdmin(),
            'preferences' => []
        ];

        return new JsonResponse($response);
    }

    /**
     * @Route("/{songID}/play", name="me_song_play", methods={"GET"})
     * @return Response
     */
    public function songAction(string $songID)
    {
        $songController = new SongController();
        $songController->setContainer($this->container);

        return $songController->song($songID);
    }
    /**
     * @Route("/{songID}/info", name="me_info", methods={"GET"})
     * @return Response
     */
    public function infoAction(string $songID)
    {
        return new JsonResponse([
            'lyrics' => '',
            'album_info' => false,
            'artist_info' => false,
            'youtube' => false,
        ]);
    }
}
