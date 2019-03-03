<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $userEmail;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phoneSsid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $userEnable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $userAware;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="companyUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userCompany;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="roleUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userRole;


    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->userEnable = false;
        $this->userAware = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): self
    {
        $this->email = $userEmail;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->userEmail;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getPhoneSsid(): ?string
    {
        return $this->phoneSsid;
    }

    public function setPhoneSsid(string $phoneSsid): self
    {
        $this->phoneSsid = $phoneSsid;

        return $this;
    }

    public function getUserEnable(): ?bool
    {
        return $this->userEnable;
    }

    public function setUserEnable(bool $userEnable): self
    {
        $this->userEnable = $userEnable;

        return $this;
    }

    public function getUserAware(): ?bool
    {
        return $this->userAware;
    }

    public function setUserAware(bool $userAware): self
    {
        $this->user_aware = $userAware;

        return $this;
    }

    public function getUserCompany(): ?Company
    {
        return $this->userCompany;
    }

    public function setUserCompany(?Company $userCompany): self
    {
        $this->userCompany = $userCompany;

        return $this;
    }

    public function getUserRole(): ?Role
    {
        return $this->userRole;
    }

    public function setUserRole(?Role $userRole): self
    {
        $this->userRole = $userRole;

        return $this;
    }

}
