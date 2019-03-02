<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegisterCodesRepository")
 */
class RegisterCodes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $registerId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeContent;

    /**
     * @ORM\Column(type="guid")
     */
    private $codeUuid;

    public function getRegisterId(): ?int
    {
        return $this->registerId;
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

    public function getCodeUuid(): ?string
    {
        return $this->codeUuid;
    }

    public function setCodeUuid(string $codeUuid): self
    {
        $this->codeUuid = $codeUuid;

        return $this;
    }
}
