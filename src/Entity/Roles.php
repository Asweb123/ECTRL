<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RolesRepository")
 */
class Roles
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
    private $roleTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roleDescription;

    /**
     * @ORM\Column(type="guid")
     */
    private $roleUuid;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="userRole", orphanRemoval=true)
     */
    private $roleUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RegisterCodes", mappedBy="RegisterCodeRole", orphanRemoval=true)
     */
    private $RoleRegisterCode;

    public function __construct()
    {
        $this->roleUsers = new ArrayCollection();
        $this->RoleRegisterCode = new ArrayCollection();
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

    public function getRoleUuid(): ?string
    {
        return $this->roleUuid;
    }

    public function setRoleUuid(string $roleUuid): self
    {
        $this->roleUuid = $roleUuid;

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
     * @return Collection|RegisterCodes[]
     */
    public function getRoleRegisterCode(): Collection
    {
        return $this->RoleRegisterCode;
    }

    public function addRoleRegisterCode(RegisterCodes $roleRegisterCode): self
    {
        if (!$this->RoleRegisterCode->contains($roleRegisterCode)) {
            $this->RoleRegisterCode[] = $roleRegisterCode;
            $roleRegisterCode->setRegisterCodeRole($this);
        }

        return $this;
    }

    public function removeRoleRegisterCode(RegisterCodes $roleRegisterCode): self
    {
        if ($this->RoleRegisterCode->contains($roleRegisterCode)) {
            $this->RoleRegisterCode->removeElement($roleRegisterCode);
            // set the owning side to null (unless already changed)
            if ($roleRegisterCode->getRegisterCodeRole() === $this) {
                $roleRegisterCode->setRegisterCodeRole(null);
            }
        }

        return $this;
    }
}
