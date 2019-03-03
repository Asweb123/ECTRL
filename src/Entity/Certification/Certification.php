<?php

namespace App\Entity\Certification;

use App\Entity\Company;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Certification\CertificationRepository")
 */
class Certification
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
    private $certifTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $certifDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Company", inversedBy="certifications")
     */
    private $certifications;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->certifications = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCertifTitle(): ?string
    {
        return $this->certifTitle;
    }

    public function setCertifTitle(string $certifTitle): self
    {
        $this->certifTitle = $certifTitle;

        return $this;
    }

    public function getCertifDescription(): ?string
    {
        return $this->certifDescription;
    }

    public function setCertifDescription(string $certifDescription): self
    {
        $this->certifDescription = $certifDescription;

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
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Company $certification): self
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications[] = $certification;
        }

        return $this;
    }

    public function removeCertification(Company $certification): self
    {
        if ($this->certifications->contains($certification)) {
            $this->certifications->removeElement($certification);
        }

        return $this;
    }
}
