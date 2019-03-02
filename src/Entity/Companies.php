<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CompaniesRepository")
 */
class Companies
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
    private $companyName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyPlan;

    /**
     * @ORM\Column(type="guid")
     */
    private $companyUuid;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="userCompany", orphanRemoval=true)
     */
    private $companyUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RegisterCodes", mappedBy="RegisterCodeCompany", orphanRemoval=true)
     */
    private $companieRegisterCodes;

    public function __construct()
    {
        $this->companyUsers = new ArrayCollection();
        $this->companieRegisterCodes = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getCompanyPlan(): ?string
    {
        return $this->companyPlan;
    }

    public function setCompanyPlan(string $companyPlan): self
    {
        $this->companyPlan = $companyPlan;

        return $this;
    }

    public function getCompanyUuid(): ?string
    {
        return $this->companyUuid;
    }

    public function setCompanyUuid(string $companyUuid): self
    {
        $this->companyUuid = $companyUuid;

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
     * @return Collection|RegisterCodes[]
     */
    public function getCompanieRegisterCodes(): Collection
    {
        return $this->companieRegisterCodes;
    }

    public function addCompanieRegisterCode(RegisterCodes $companieRegisterCode): self
    {
        if (!$this->companieRegisterCodes->contains($companieRegisterCode)) {
            $this->companieRegisterCodes[] = $companieRegisterCode;
            $companieRegisterCode->setRegisterCodeCompany($this);
        }

        return $this;
    }

    public function removeCompanieRegisterCode(RegisterCodes $companieRegisterCode): self
    {
        if ($this->companieRegisterCodes->contains($companieRegisterCode)) {
            $this->companieRegisterCodes->removeElement($companieRegisterCode);
            // set the owning side to null (unless already changed)
            if ($companieRegisterCode->getRegisterCodeCompany() === $this) {
                $companieRegisterCode->setRegisterCodeCompany(null);
            }
        }

        return $this;
    }

}
