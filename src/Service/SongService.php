<?php
/**
 * Coded by fionera.
 */

namespace App\Service;

use App\Entity\Song;
use League\Flysystem\Filesystem;

class SongService
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * SongService constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param Song $song
     * @return false|resource
     */
    public function streamSong(Song $song)
    {
        return $this->filesystem->get($song->getPath())->readStream();
    }

    /**
     * @param Song $song
     * @return false|string
     */
    public function readSong(Song $song)
    {
        return $this->filesystem->get($song->getPath())->read();
    }


    public function getSongSize(Song $song)
    {
        return $this->filesystem->get($song->getPath())->getSize();
    }
}