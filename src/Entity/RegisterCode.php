<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegisterCodeRepository")
 * @UniqueEntity("codeContent", message="Ce code d'enregistrement est déjà utilisé.")
 */
class RegisterCode
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
     *     min = 8,
     *     max = 8,
     *     exactMessage="Le code d'enregistrement doit contenir exactement {{ limit }} caractères."
     * )
     */
    private $codeContent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="registerCodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="registerCodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur renseignée n'est pas un nombre entier."
     * )
     * @Assert\GreaterThan(0)
     */
    private $maxNbOfUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="registerCode")
     */
    private $users;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->users = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCodeContent(): ?string
    {
        return $this->codeContent;
    }

    public function setCodeContent(string $codeContent): self
    {
        $this->codeContent = $codeContent;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getMaxNbOfUsers(): ?int
    {
        return $this->maxNbOfUsers;
    }

    public function setMaxNbOfUsers(int $maxNbOfUsers): self
    {
        $this->maxNbOfUsers = $maxNbOfUsers;

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
            $user->setRegisterCode($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getRegisterCode() === $this) {
                $user->setRegisterCode(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->codeContent;
    }
}
