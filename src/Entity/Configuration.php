<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigurationRepository")
 */
class Configuration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="integer")
     */
    private $enabled;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }
    
    /**
     * __toString
     *
     * @return void
     */
    public function __toString()
    {
        return $this->getName() ?: '-';
    }
    
    /**
     * getId
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * getName
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    
    /**
     * setName
     *
     * @param  mixed $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * getValue
     *
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
    
    /**
     * setValue
     *
     * @param  mixed $value
     * @return self
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
    
    /**
     * getEnabled
     *
     * @return int
     */
    public function getEnabled(): ?int
    {
        return $this->enabled;
    }
    
    /**
     * setEnabled
     *
     * @param  mixed $enabled
     * @return self
     */
    public function setEnabled(int $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
    
    /**
     * getCreatedAt
     *
     * @return DateTimeInterface
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
