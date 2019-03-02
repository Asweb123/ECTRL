<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=255)
     */
    private $companyPlan;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="userCompany", orphanRemoval=true)
     */
    private $companyUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RegisterCode", mappedBy="RegisterCodeCompany", orphanRemoval=true)
     */
    private $companyRegisterCodes;

    public function __construct()
    {
        $this->companyUsers = new ArrayCollection();
        $this->companyRegisterCodes = new ArrayCollection();
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
}
