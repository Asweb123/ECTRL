<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 */
class Result
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Range(
     *      min = 0,
     *      max = 3
     * )
     */
    private $state;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Audit", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $audit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Requirement", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $requirement;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->state = 0;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

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

    public function getAudit(): ?Audit
    {
        return $this->audit;
    }

    public function setAudit(?Audit $audit): self
    {
        $this->audit = $audit;

        return $this;
    }

    public function getRequirement(): ?Requirement
    {
        return $this->requirement;
    }

    public function setRequirement(?Requirement $requirement): self
    {
        $this->requirement = $requirement;

        return $this;
    }
}
