<?php

namespace App\Entity;

use App\Repository\ChargesSocialPersonnelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChargesSocialPersonnelRepository::class)
 * @ORM\Table(name="v_chargessocial_personnel")
 */
class ChargesSocialPersonnel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $designation;

    /**
     * @ORM\Column(type="integer")
     */
    private $personnelId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPersonnelId(): ?int
    {
        return $this->personnelId;
    }

    public function setPersonnelId(int $personnelId): self
    {
        $this->personnelId = $personnelId;

        return $this;
    }
}
