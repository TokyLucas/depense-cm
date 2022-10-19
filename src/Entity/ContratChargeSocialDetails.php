<?php

namespace App\Entity;

use App\Repository\ContratChargeSocialDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratChargeSocialDetailsRepository::class)
 * @ORM\Table(name="v_contratchargesocial")
 */
class ContratChargeSocialDetails
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
     * @ORM\Column(type="decimal", precision=3, scale=1)
     */
    private $part_salariale;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=1)
     */
    private $part_patronale;

    /**
     * @ORM\Column(type="integer")
     */
    private $contrat_id;

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPartSalariale(): ?string
    {
        return $this->part_salariale;
    }

    public function setPartSalariale(string $part_salariale): self
    {
        $this->part_salariale = $part_salariale;

        return $this;
    }

    public function getPartPatronale(): ?string
    {
        return $this->part_patronale;
    }

    public function setPartPatronale(string $part_patronale): self
    {
        $this->part_patronale = $part_patronale;

        return $this;
    }

    public function getContratId(): ?int
    {
        return $this->contrat_id;
    }

    public function setContratId(int $contrat_id): self
    {
        $this->contrat_id = $contrat_id;

        return $this;
    }
}
