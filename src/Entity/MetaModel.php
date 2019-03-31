<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MetaModelRepository")
 */
class MetaModel
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
    private $AuditTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $AuditDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ThemeTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ThemeDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Requirement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuditTitle(): ?string
    {
        return $this->AuditTitle;
    }

    public function setAuditTitle(string $AuditTitle): self
    {
        $this->AuditTitle = $AuditTitle;

        return $this;
    }

    public function getAuditDescription(): ?string
    {
        return $this->AuditDescription;
    }

    public function setAuditDescription(string $AuditDescription): self
    {
        $this->AuditDescription = $AuditDescription;

        return $this;
    }

    public function getThemeTitle(): ?string
    {
        return $this->ThemeTitle;
    }

    public function setThemeTitle(string $ThemeTitle): self
    {
        $this->ThemeTitle = $ThemeTitle;

        return $this;
    }

    public function getThemeDescription(): ?string
    {
        return $this->ThemeDescription;
    }

    public function setThemeDescription(string $ThemeDescription): self
    {
        $this->ThemeDescription = $ThemeDescription;

        return $this;
    }

    public function getRequirement(): ?string
    {
        return $this->Requirement;
    }

    public function setRequirement(string $Requirement): self
    {
        $this->Requirement = $Requirement;

        return $this;
    }
}
