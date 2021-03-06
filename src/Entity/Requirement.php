<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequirementRepository")
 */
class Requirement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

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
    private $rankTheme;

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
 //   private $rankCertification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Certification", inversedBy="requirements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $certification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Theme", inversedBy="requirements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="requirement", orphanRemoval=true)
     */
    private $results;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->results = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getRankTheme(): ?int
    {
        return $this->rankTheme;
    }

    public function setRankTheme(int $rankTheme): self
    {
        $this->rankTheme = $rankTheme;

        return $this;
    }

 //   public function getRankCertification(): ?int
 //   {
 //       return $this->rankCertification;
 //   }
 //
 //   public function setRankCertification(int $rankCertification): self
 //   {
 //       $this->rankCertification = $rankCertification;
 //
 //       return $this;
 //   }

    public function getCertification(): ?Certification
    {
        return $this->certification;
    }

    public function setCertification(?Certification $certification): self
    {
        $this->certification = $certification;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResponses(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setRequirement($this);
        }

        return $this;
    }

    public function removeResponse(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getRequirement() === $this) {
                $result->setRequirement(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->description;
    }
}
