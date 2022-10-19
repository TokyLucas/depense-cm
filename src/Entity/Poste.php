<?php

namespace App\Entity;

use App\Repository\PosteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PosteRepository::class)
 */
class Poste
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
    private $contrat_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $categorie;

    /**
     * @ORM\Column(type="string")
     */
    private $indice;

    /**
     * @ORM\Column(type="integer")
     */
    private $direction_id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $matricule;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $service_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $designation;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $grade;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContratId(): ?int
    {
        return $this->contrat_id;
    }

    public function setContratId(?int $contrat_id): self
    {
        $this->contrat_id = $contrat_id;

        return $this;
    }

    public function getCategorie(): ?int
    {
        return $this->categorie;
    }

    public function setCategorie(?int $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getIndice(): ?string
    {
        return $this->indice;
    }

    public function setIndice(?string $indice): self
    {
        $this->indice = $indice;

        return $this;
    }

    public function getDirectionId(): ?int
    {
        return $this->direction_id;
    }

    public function setDirectionId(?int $direction_id): self
    {
        $this->direction_id = $direction_id;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getServiceId(): ?int
    {
        return $this->service_id;
    }

    public function setServiceId(?int $service_id): self
    {
        $this->service_id = $service_id;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }
}
