<?php

namespace App\Entity;

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
    private $companyId;

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

    public function getCompanyId(): ?int
    {
        return $this->companyId;
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

}
