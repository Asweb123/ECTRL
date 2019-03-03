<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegisterCodeRepository")
 */
class RegisterCode
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
     * @ORM\Column(type="string", length=255)
     */
    private $codeContent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="companyRegisterCodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $RegisterCodeCompany;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="roleRegisterCodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $RegisterCodeRole;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCodeContent(): ?string
    {
        return $this->codeContent;
    }

    public function setCodeContent(string $codeContent): self
    {
        $this->codeContent = $codeContent;

        return $this;
    }

    public function getRegisterCodeCompany(): ?Company
    {
        return $this->RegisterCodeCompany;
    }

    public function setRegisterCodeCompany(?Company $RegisterCodeCompany): self
    {
        $this->RegisterCodeCompany = $RegisterCodeCompany;

        return $this;
    }

    public function getRegisterCodeRole(): ?Role
    {
        return $this->RegisterCodeRole;
    }

    public function setRegisterCodeRole(?Role $RegisterCodeRole): self
    {
        $this->RegisterCodeRole = $RegisterCodeRole;

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
}
