<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $key;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $value;

    /**
     * Setting constructor.
     * @param $key
     * @param string $value
     */
    public function __construct($key, ?string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}
