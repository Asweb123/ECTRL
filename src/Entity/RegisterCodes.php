<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegisterCodesRepository")
 */
class RegisterCodes
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
    private $codeContent;

    /**
     * @ORM\Column(type="guid")
     */
    private $codeUuid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Companies", inversedBy="companieRegisterCodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $RegisterCodeCompany;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Roles", inversedBy="RoleRegisterCode")
     * @ORM\JoinColumn(nullable=false)
     */
    private $RegisterCodeRole;

    public function getId(): ?int
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

    public function getCodeUuid(): ?string
    {
        return $this->codeUuid;
    }

    public function setCodeUuid(string $codeUuid): self
    {
        $this->codeUuid = $codeUuid;

        return $this;
    }

    public function getRegisterCodeCompany(): ?Companies
    {
        return $this->RegisterCodeCompany;
    }

    public function setRegisterCodeCompany(?Companies $RegisterCodeCompany): self
    {
        $this->RegisterCodeCompany = $RegisterCodeCompany;

        return $this;
    }

    public function getRegisterCodeRole(): ?Roles
    {
        return $this->RegisterCodeRole;
    }

    public function setRegisterCodeRole(?Roles $RegisterCodeRole): self
    {
        $this->RegisterCodeRole = $RegisterCodeRole;

        return $this;
    }
}
