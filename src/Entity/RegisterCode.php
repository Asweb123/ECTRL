<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegisterCodeRepository")
 */
class RegisterCode
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="companyRegisterCodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $RegisterCodeCompany;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="roleRegisterCodes")
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
}
