<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * @UniqueEntity("codeContent", message="Ce code d'enregistrement est déjà utilisé.")
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Certification", mappedBy="companies")
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Audit", mappedBy="company", orphanRemoval=true)
     */
    private $audits;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->certifications = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->registerCodes = new ArrayCollection();
        $this->audits = new ArrayCollection();
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
            $audit->setCompany($this);
        }

        return $this;
    }

    public function removeAudit(Audit $audit): self
    {
        if ($this->audits->contains($audit)) {
            $this->audits->removeElement($audit);
            // set the owning side to null (unless already changed)
            if ($audit->getCompany() === $this) {
                $audit->setCompany(null);
            }
        }

        return $this;
    }
}
