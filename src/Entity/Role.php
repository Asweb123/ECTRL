<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
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
    private $roleTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roleDescription;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="userRole", orphanRemoval=true)
     */
    private $roleUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RegisterCode", mappedBy="RegisterCodeRole", orphanRemoval=true)
     */
    private $roleRegisterCodes;

    public function __construct()
    {
        $this->roleUsers = new ArrayCollection();
        $this->roleRegisterCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleTitle(): ?string
    {
        return $this->roleTitle;
    }

    public function setRoleTitle(string $roleTitle): self
    {
        $this->roleTitle = $roleTitle;

        return $this;
    }

    public function getRoleDescription(): ?string
    {
        return $this->roleDescription;
    }

    public function setRoleDescription(string $roleDescription): self
    {
        $this->roleDescription = $roleDescription;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRoleUsers(): Collection
    {
        return $this->roleUsers;
    }

    public function addRoleUser(User $roleUser): self
    {
        if (!$this->roleUsers->contains($roleUser)) {
            $this->roleUsers[] = $roleUser;
            $roleUser->setUserRole($this);
        }

        return $this;
    }

    public function removeRoleUser(User $roleUser): self
    {
        if ($this->roleUsers->contains($roleUser)) {
            $this->roleUsers->removeElement($roleUser);
            // set the owning side to null (unless already changed)
            if ($roleUser->getUserRole() === $this) {
                $roleUser->setUserRole(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RegisterCode[]
     */
    public function getRoleRegisterCodes(): Collection
    {
        return $this->roleRegisterCodes;
    }

    public function addRoleRegisterCode(RegisterCode $roleRegisterCode): self
    {
        if (!$this->roleRegisterCodes->contains($roleRegisterCode)) {
            $this->roleRegisterCodes[] = $roleRegisterCode;
            $roleRegisterCode->setRegisterCodeRole($this);
        }

        return $this;
    }

    public function removeRoleRegisterCode(RegisterCode $roleRegisterCode): self
    {
        if ($this->roleRegisterCodes->contains($roleRegisterCode)) {
            $this->roleRegisterCodes->removeElement($roleRegisterCode);
            // set the owning side to null (unless already changed)
            if ($roleRegisterCode->getRegisterCodeRole() === $this) {
                $roleRegisterCode->setRegisterCodeRole(null);
            }
        }

        return $this;
    }
}
