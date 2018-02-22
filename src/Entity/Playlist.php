<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaylistRepository")
 */
class Playlist
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="id")
     */
    private $userID;

    /**
     * @var Song[]
     * @ORM\OneToMany(targetEntity="App\Entity\Song", mappedBy="id")
     * @ORM\JoinTable(name="playlist_songs",
     *      joinColumns={@ORM\JoinColumn(name="playlist_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="song_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $songIDs;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Setting constructor.
     * @param User $userID
     * @param string $name
     * @param Song[] $songIDs
     */
    public function __construct(User $userID, string $name, array $songIDs = [])
    {
        $this->userID = $userID;
        $this->songIDs = new ArrayCollection($songIDs);
        $this->name = $name;
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
     * @return User
     */
    public function getUserID(): User
    {
        return $this->userID;
    }

    /**
     * @param User $userID
     */
    public function setUserID(User $userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return Song[]
     */
    public function getSongIDs(): array
    {
        return $this->songIDs;
    }

    /**
     * @param Song[] $songIDs
     */
    public function setSongIDs(array $songIDs): void
    {
        $this->songIDs = $songIDs;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
