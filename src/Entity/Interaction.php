<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InteractionRepository")
 */
class Interaction
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $userID;

    /**
     * @var Song
     * @ORM\ManyToOne(targetEntity="App\Entity\Song")
     */
    private $songID;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $liked;

    /**
     * @var int
     * @ORM\Column(type="string")
     */
    private $playCount;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Interaction constructor.
     * @param User $userID
     * @param Song $songID
     * @param bool $liked
     * @param int $playCount
     */
    public function __construct(User $userID, Song $songID, bool $liked = false, int $playCount = 0)
    {
        $this->userID = $userID;
        $this->songID = $songID;
        $this->liked = $liked;
        $this->playCount = $playCount;
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
     * @return Song
     */
    public function getSongID(): Song
    {
        return $this->songID;
    }

    /**
     * @param Song $songID
     */
    public function setSongID(Song $songID): void
    {
        $this->songID = $songID;
    }

    /**
     * @return bool
     */
    public function getLiked(): bool
    {
        return $this->liked;
    }

    /**
     * @param bool $liked
     */
    public function setLiked(bool $liked): void
    {
        $this->liked = $liked;
    }

    /**
     * @return int
     */
    public function getPlayCount(): int
    {
        return $this->playCount;
    }

    /**
     * @param int $playCount
     */
    public function setPlayCount(int $playCount): void
    {
        $this->playCount = $playCount;
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
