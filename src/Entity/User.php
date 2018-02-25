<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @Assert\Email(
     *     strict="true",
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * User constructor.
     * @param string $name
     * @param string $email
     * @param string $password
     * @param bool $isAdmin
     */
    public function __construct(string $name, string $email, string $password, bool $isAdmin = false)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
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

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[]|string[] The user roles
     */
    public function getRoles()
    {
        $roles = ['ROLE_USER'];
        if ($this->isAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->name,
            $this->email,
            $this->password,
            $this->isAdmin,
            $this->createdAt
        ]);
    }

    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->name,
            $this->email,
            $this->password,
            $this->isAdmin,
            $this->createdAt
        ] = unserialize($serialized, ['allowed_classes' => [self::class]]);
    }
}
