<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SongRepository")
 */
class Song
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @var Artist
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist")
     */
    private $artistID;

    /**
     * @var Album
     * @ORM\ManyToOne(targetEntity="App\Entity\Album")
     */
    private $albumID;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $length;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $track;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $disc;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $path;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Song constructor.
     * @param Artist $artistID
     * @param Album $albumID
     * @param string $title
     * @param string $path
     * @param int $length
     * @param int $track
     * @param int $disc
     */
    public function __construct(Artist $artistID, Album $albumID, string $title, string $path, int $length, int $track = 1, int $disc = 0)
    {
        $this->artistID = $artistID;
        $this->albumID = $albumID;
        $this->title = $title;
        $this->length = $length;
        $this->track = $track;
        $this->disc = $disc;
        $this->path = $path;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Album
     */
    public function getAlbumID(): Album
    {
        return $this->albumID;
    }

    /**
     * @param Album $albumID
     */
    public function setAlbumID(Album $albumID): void
    {
        $this->albumID = $albumID;
    }

    /**
     * @return Artist
     */
    public function getArtistID(): Artist
    {
        return $this->artistID;
    }

    /**
     * @param Artist $artistID
     */
    public function setArtistID(Artist $artistID): void
    {
        $this->artistID = $artistID;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getTrack(): int
    {
        return $this->track;
    }

    /**
     * @param int $track
     */
    public function setTrack(int $track): void
    {
        $this->track = $track;
    }

    /**
     * @return int
     */
    public function getDisc(): int
    {
        return $this->disc;
    }

    /**
     * @param int $disc
     */
    public function setDisc(int $disc): void
    {
        $this->disc = $disc;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
