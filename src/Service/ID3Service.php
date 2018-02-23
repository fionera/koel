<?php

namespace App\Service;


use League\Flysystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ID3Service
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \getID3
     */
    private $ID3Tag;

    /**
     * @var Filesystem
     */
    private $filesystem;


    /**
     * ID3Service constructor.
     */
    public function __construct(Filesystem $filesystem)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $this->ID3Tag = new \getID3();
        $this->filesystem = $filesystem;
    }

    public function getTagsFromFile(string $path)
    {
        $this->filesystem->get($path);

        $tempFile = tempnam(sys_get_temp_dir(), 'tmpsong');

        $handle = fopen($tempFile, 'wb');
        fwrite($handle, $this->filesystem->get($path)->read());
        fclose($handle);

        $fileInformations = $this->ID3Tag->analyze($tempFile);

        unlink($tempFile);

        $track = $this->getOrDefault($fileInformations, 'tags.id3v2.track_number.0', '1');
        if (substr_count($track, '/') + 1 > 1) {
            $track = explode('/', $track)[0];
        }

        $tags = [
            'artist' => $this->getOrDefault($fileInformations, 'tags.id3v2.artist.0', 'Unknown Artist'),
            'album' => $this->getOrDefault($fileInformations, 'tags.id3v2.album.0', 'Unknown Album'),
            'title' => $this->getOrDefault($fileInformations, 'tags.id3v2.title.0', basename($path)),
            'length' => round($fileInformations['playtime_seconds']),
            'track' => $track
        ];

        return $tags;
    }

    private function getOrDefault($array, $key, $default = null)
    {
        if (null === $key) {
            return $array;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }
}