<?php

namespace App\Entity;

use App\Repository\PersonnelPosteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonnelPosteRepository::class)
 */
class PersonnelPoste
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $personnel_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $poste_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonnelId(): ?int
    {
        return $this->personnel_id;
    }

    public function setPersonnelId(int $personnel_id): self
    {
        $this->personnel_id = $personnel_id;

        return $this;
    }

    public function getPosteId(): ?int
    {
        return $this->poste_id;
    }

    public function setPosteId(int $poste_id): self
    {
        $this->poste_id = $poste_id;

        return $this;
    }
}
