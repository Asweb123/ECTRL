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
    private $companyName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="userCompany", orphanRemoval=true)
     */
    private $companyUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RegisterCode", mappedBy="RegisterCodeCompany", orphanRemoval=true)
     */
    private $companyRegisterCodes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Certification\Certification", mappedBy="certifications")
     */
    private $certifications;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->companyUsers = new ArrayCollection();
        $this->companyRegisterCodes = new ArrayCollection();
        $this->certifications = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getCompanyUsers(): Collection
    {
        return $this->companyUsers;
    }

    public function addCompanyUser(User $companyUser): self
    {
        if (!$this->companyUsers->contains($companyUser)) {
            $this->companyUsers[] = $companyUser;
            $companyUser->setUserCompany($this);
        }

        return $this;
    }

    public function removeCompanyUser(User $companyUser): self
    {
        if ($this->companyUsers->contains($companyUser)) {
            $this->companyUsers->removeElement($companyUser);
            // set the owning side to null (unless already changed)
            if ($companyUser->getUserCompany() === $this) {
                $companyUser->setUserCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RegisterCode[]
     */
    public function getCompanyRegisterCodes(): Collection
    {
        return $this->companyRegisterCodes;
    }

    public function addCompanyRegisterCode(RegisterCode $companyRegisterCode): self
    {
        if (!$this->companyRegisterCodes->contains($companyRegisterCode)) {
            $this->companyRegisterCodes[] = $companyRegisterCode;
            $companyRegisterCode->setRegisterCodeCompany($this);
        }

        return $this;
    }

    public function removeCompanyRegisterCode(RegisterCode $companyRegisterCode): self
    {
        if ($this->companyRegisterCodes->contains($companyRegisterCode)) {
            $this->companyRegisterCodes->removeElement($companyRegisterCode);
            // set the owning side to null (unless already changed)
            if ($companyRegisterCode->getRegisterCodeCompany() === $this) {
                $companyRegisterCode->setRegisterCodeCompany(null);
            }
        }

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
            $certification->addCertification($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): self
    {
        if ($this->certifications->contains($certification)) {
            $this->certifications->removeElement($certification);
            $certification->removeCertification($this);
        }

        return $this;
    }
}
