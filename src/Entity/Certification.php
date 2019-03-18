<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CertificationRepository")
 * @UniqueEntity("title", message="Ce nom de certification est déjà utilisé.")
 */
class Certification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(
     *     min = 1,
     *     max = 255,
     *     minMessage="Le titre de la certification doit contenir au moins {{ limit }} caractère.",
     *     maxMessage="Le titre de la certification doit contenir au maximum {{ limit }} caractères."
     * )
     *
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(
     *     min = 1,
     *     max = 500,
     *     minMessage="Le titre de la certification doit contenir au moins {{ limit }} caractère.",
     *     maxMessage="Le titre de la certification doit contenir au maximum {{ limit }} caractères."
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Company", inversedBy="certifications")
     */
    private $companies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Audit", mappedBy="certification", orphanRemoval=true)
     */
    private $audits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Theme", mappedBy="certification", orphanRemoval=true)
     */
    private $themes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Requirement", mappedBy="certification", orphanRemoval=true)
     */
    private $requirements;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->companies = new ArrayCollection();
        $this->audits = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->requirements = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->contains($company)) {
            $this->companies->removeElement($company);
        }

        return $this;
    }

    /**
     * @return Collection|Audit[]
     */
    public function getAudits(): Collection
    {
        return $this->audits;
    }

    public function addAudit(Audit $audit): self
    {
        if (!$this->audits->contains($audit)) {
            $this->audits[] = $audit;
            $audit->setCertification($this);
        }

        return $this;
    }

    public function removeAudit(Audit $audit): self
    {
        if ($this->audits->contains($audit)) {
            $this->audits->removeElement($audit);
            // set the owning side to null (unless already changed)
            if ($audit->getCertification() === $this) {
                $audit->setCertification(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Theme[]
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->themes->contains($theme)) {
            $this->themes[] = $theme;
            $theme->setCertification($this);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        if ($this->themes->contains($theme)) {
            $this->themes->removeElement($theme);
            // set the owning side to null (unless already changed)
            if ($theme->getCertification() === $this) {
                $theme->setCertification(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Requirement[]
     */
    public function getRequirements(): Collection
    {
        return $this->requirements;
    }

    public function addRequirement(Requirement $requirement): self
    {
        if (!$this->requirements->contains($requirement)) {
            $this->requirements[] = $requirement;
            $requirement->setCertification($this);
        }

        return $this;
    }

    public function removeRequirement(Requirement $requirement): self
    {
        if ($this->requirements->contains($requirement)) {
            $this->requirements->removeElement($requirement);
            // set the owning side to null (unless already changed)
            if ($requirement->getCertification() === $this) {
                $requirement->setCertification(null);
            }
        }

        return $this;
    }
}
