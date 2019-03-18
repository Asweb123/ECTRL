<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(
     *     min = 1,
     *     max = 255,
     *     minMessage="Le titre de la certification doit contenir au moins {{ limit }} caractère.",
     *     maxMessage="Le titre de la certification doit contenir au maximum {{ limit }} caractères."
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="role")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RegisterCode", mappedBy="role", orphanRemoval=true)
     */
    private $registerCodes;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = 1,
     *      max = 3,
     *      minMessage = "Le niveau hiérarchique de ce rôle ne peut pas être plus petit que {{ limit }}.",
     *      maxMessage = "Le niveau hiérarchique de ce rôle ne peut pas être plus grand que {{ limit }}."
     * )
     */
    private $rank;

    public function __construct()
    {
        $this->creationDate = new \DateTime("now");
        $this->users = new ArrayCollection();
        $this->registerCodes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRole(null);
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
            $registerCode->setRole($this);
        }

        return $this;
    }

    public function removeRegisterCode(RegisterCode $registerCode): self
    {
        if ($this->registerCodes->contains($registerCode)) {
            $this->registerCodes->removeElement($registerCode);
            // set the owning side to null (unless already changed)
            if ($registerCode->getRole() === $this) {
                $registerCode->setRole(null);
            }
        }

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }
}
