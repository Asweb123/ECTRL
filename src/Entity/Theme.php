<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ThemeRepository")
 */
class Theme
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
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur renseignée n'est pas un nombre entier."
     * )
     * @Assert\GreaterThan(0)
     */
    private $rankCertification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Certification", inversedBy="themes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $certification;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Requirement", mappedBy="theme", orphanRemoval=true)
     */
    private $requirements;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Regex("/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/")
     */
    private $color;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
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

    public function setDescription(?string $description): self
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

    public function getRankCertification(): ?int
    {
        return $this->rankCertification;
    }

    public function setRankCertification(int $rankCertification): self
    {
        $this->rankCertification = $rankCertification;

        return $this;
    }

    public function getCertification(): ?Certification
    {
        return $this->certification;
    }

    public function setCertification(?Certification $certification): self
    {
        $this->certification = $certification;

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
            $requirement->setTheme($this);
        }

        return $this;
    }

    public function removeRequirement(Requirement $requirement): self
    {
        if ($this->requirements->contains($requirement)) {
            $this->requirements->removeElement($requirement);
            // set the owning side to null (unless already changed)
            if ($requirement->getTheme() === $this) {
                $requirement->setTheme(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->title;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
