<?php

namespace App\Entity;

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
    private $roleId;

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

    public function getRoleId(): ?int
    {
        return $this->roleId;
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
}
