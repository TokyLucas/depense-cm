<?php

namespace App\Entity;

use App\Repository\IndemnitePersonnelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndemnitePersonnelRepository::class)
 * @ORM\Table(name="v_indemnite_personnel")
 */
class IndemnitePersonnel
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

    /**
     * @ORM\Column(type="integer")
     */
    private $IndemniteId;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $montant;

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

    public function getIndemniteId(): ?int
    {
        return $this->IndemniteId;
    }

    public function setIndemniteId(int $IndemniteId): self
    {
        $this->IndemniteId = $IndemniteId;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }
}
