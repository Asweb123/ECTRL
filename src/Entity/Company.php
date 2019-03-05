<?php

namespace App\Entity;

use App\Entity\Certification\Certification;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Certification\Certification", mappedBy="companies")
     */
    private $certifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="company", orphanRemoval=true)
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RegisterCode", mappedBy="company", orphanRemoval=true)
     */
    private $registerCodes;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->certifications = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->registerCodes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|Certification[]
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certification $certification): self
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications[] = $certification;
            $certification->addCompany($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): self
    {
        if ($this->certifications->contains($certification)) {
            $this->certifications->removeElement($certification);
            $certification->removeCompany($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RegisterCode[]
     */
    public function getRegisterCodes(): Collection
    {
        return $this->registerCodes;
    }

    public function addRegisterCode(RegisterCode $registerCode): self
    {
        if (!$this->registerCodes->contains($registerCode)) {
            $this->registerCodes[] = $registerCode;
            $registerCode->setCompany($this);
        }

        return $this;
    }

    public function removeRegisterCode(RegisterCode $registerCode): self
    {
        if ($this->registerCodes->contains($registerCode)) {
            $this->registerCodes->removeElement($registerCode);
            // set the owning side to null (unless already changed)
            if ($registerCode->getCompany() === $this) {
                $registerCode->setCompany(null);
            }
        }

        return $this;
    }
}
