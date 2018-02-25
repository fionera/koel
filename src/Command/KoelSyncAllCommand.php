<?php

namespace App\Command;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;
use App\Service\ID3Service;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class KoelSyncAllCommand extends Command
{
    protected static $defaultName = 'koel:sync:all';
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var ID3Service
     */
    private $ID3Service;

    /**
     * KoelSyncAllCommand constructor.
     * @param Filesystem $filesystem
     * @param EntityManager $entityManager
     * @param ID3Service $ID3Service
     */
    public function __construct(Filesystem $filesystem, EntityManagerInterface $entityManager, ID3Service $ID3Service)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->entityManager = $entityManager;
        $this->ID3Service = $ID3Service;
    }


    protected function configure()
    {
        $this
            ->setDescription('Scan the whole Library');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $fileList = $this->filesystem->getAdapter()->listContents('', true);

        $artistRepo = $this->entityManager->getRepository(Artist::class);
        $albumRepo = $this->entityManager->getRepository(Album::class);
        $songRepo = $this->entityManager->getRepository(Song::class);


        /**
         * Cache for all Artists that are requested or created
         * @var Artist[]
         */
        $artists = [];

        /**
         * Cache for all Albums that are requested or created
         * @var Album[]
         */
        $albums = [];

        /**
         * Filepaths for all Files with Errors
         * @var string[]
         */
        $errors = [];

        $bar = $io->createProgressBar(count($fileList));
        $bar->start();
        foreach ($fileList as $file) {
            $filePath = $file['path'];
            if (!$this->isSupported($filePath)) {
                $bar->advance();
                continue;
            }

            if ($songRepo->findOneBy(['path' => $filePath]) !== null) {
                continue;
            }

            $tags = $this->ID3Service->getTagsFromFile($filePath);

            if ($tags === null) {
                $errors[] = $filePath;
                continue;
            }

            $artist = null;
            if (!array_key_exists($tags['artist'], $artists)) {
                /** @var Artist|null $artist */
                $artist = $artistRepo->findOneBy(['name' => $tags['artist']]);
            }

            if ($artist === null) {
                $artist = new Artist($tags['artist']);
            }

            $album = null;
            if (!array_key_exists($tags['album'], $albums)) {
                /** @var Album|null $album */
                $album = $albumRepo->findOneBy(['name' => $tags['album']]);
            }

            if ($album === null) {
                $album = new Album($artist, $tags['album']);
            }

            $song = $songRepo->findOneBy(['title' => $tags['title'], 'albumID' => $album, 'artistID' => $artist]);
            if ($song === null) {
                $song = new Song($artist, $album, $tags['title'], $filePath, $tags['length'], $tags['track']);
            }

            $this->entityManager->persist($artist);
            $this->entityManager->persist($album);
            $this->entityManager->persist($song);
            $this->entityManager->flush();

            $bar->advance();
        }
        $bar->finish();

        $io->success('Scan complete');
    }

    private function isSupported(string $fileName): bool
    {
        $supported = ['flac', 'mp3'];

        $array = explode('.', $fileName);
        $ending = end($array);

        return in_array($ending, $supported, true);
    }
}
