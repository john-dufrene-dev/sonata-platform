<?php

namespace App\Entity\User;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ApiResource
 * @UniqueEntity(fields={"email"}, message="register.email.unique")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * @var string Email adress
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message = "register.email.valid")
     * @Assert\NotBlank(message = "register.email.notblank")
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "example"="new@domain.com",
     *         }
     *     }
     * )
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\Length(max=4096, maxMessage="register.max.password")
     * @Assert\Length(min=6, maxMessage="register.min.password")
     */
    private $plainPassword;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(max=4096, maxMessage="register.max.password")
     * @Assert\Length(min=6, maxMessage="register.min.password")
     */
    private $password;

    /**
     * @var string apitoken value
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
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
     * @ORM\OneToOne(targetEntity="App\Entity\User\UserInfo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $infos;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->apiToken = bin2hex(random_bytes(60));
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    /**
     * Returns a string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername() ?: '-';
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
     * getEmail
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * setEmail
     *
     * @param  mixed $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * getRoles
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE__USER';

        return array_unique($roles);
    }

    /**
     * setRoles
     *
     * @param  mixed $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * getPlainPassword
     *
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * setPlainPassword
     *
     * @param  mixed $password
     * @return void
     */
    public function setPlainPassword(?string $password)
    {
        $this->plainPassword = $password;
    }

    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * setPassword
     *
     * @param  mixed $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    /**
     * getApiToken
     *
     * @return string
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }
    
    /**
     * setApiToken
     *
     * @param  mixed $apiToken
     * @return self
     */
    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }
    
    /**
     * getEnabled
     *
     * @return bool
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }
    
    /**
     * setEnabled
     *
     * @param  mixed $enabled
     * @return self
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * getSalt
     *
     * @return void
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * eraseCredentials
     *
     * @return void
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * setCreatedAt
     *
     * @param  mixed $created_at
     * @return self
     */
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * getUpdatedAt
     *
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * setUpdatedAt
     *
     * @param  mixed $updated_at
     * @return self
     */
    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * getInfos
     *
     * @return UserInfo
     */
    public function getInfos(): ?UserInfo
    {
        return $this->infos;
    }

    /**
     * setInfos
     *
     * @param  mixed $infos
     * @return self
     */
    public function setInfos(UserInfo $infos): self
    {
        $this->infos = $infos;

        return $this;
    }
}
