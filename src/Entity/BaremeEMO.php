<?php

namespace App\Entity;

use App\Repository\BaremeEMORepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BaremeEMORepository::class)
 * @ORM\Table(name="baremepersonneltemporaire")
 */
class BaremeEMO
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $indice;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $taux_par_heure;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndice(): ?string
    {
        return $this->indice;
    }

    public function setIndice(string $indice): self
    {
        $this->indice = $indice;

        return $this;
    }

    public function getTauxParHeure(): ?string
    {
        return $this->taux_par_heure;
    }

    public function setTauxParHeure(string $taux_par_heure): self
    {
        $this->taux_par_heure = $taux_par_heure;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
