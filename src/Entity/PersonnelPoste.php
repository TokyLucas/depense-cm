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

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datedebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datefin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $heureparjour;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbjourdetravailtemporaire;

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

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getHeureparjour(): ?int
    {
        return $this->heureparjour;
    }

    public function setHeureparjour(?int $heureparjour): self
    {
        $this->heureparjour = $heureparjour;

        return $this;
    }

    public function getNbjourdetravailtemporaire(): ?int
    {
        return $this->nbjourdetravailtemporaire;
    }

    public function setNbjourdetravailtemporaire(?int $nbjourdetravailtemporaire): self
    {
        $this->nbjourdetravailtemporaire = $nbjourdetravailtemporaire;

        return $this;
    }
}
