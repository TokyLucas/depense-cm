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
     * @ORM\Column(type="integer")
     */
    private $indice;

    /**
     * @ORM\Column(type="integer")
     */
    private $direction_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategorie(): ?int
    {
        return $this->categorie;
    }

    public function setCategorie(int $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getIndice(): ?int
    {
        return $this->indice;
    }

    public function setIndice(int $indice): self
    {
        $this->indice = $indice;

        return $this;
    }

    public function getDirectionId(): ?int
    {
        return $this->direction_id;
    }

    public function setDirectionId(int $direction_id): self
    {
        $this->direction_id = $direction_id;

        return $this;
    }
}
