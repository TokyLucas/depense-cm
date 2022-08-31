<?php

namespace App\Entity;

use App\Repository\BaremeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BaremeRepository::class)
 */
class Bareme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $datebareme;

    /**
     * @ORM\Column(type="integer")
     */
    private $categorie;

    /**
     * @ORM\Column(type="integer")
     */
    private $indice;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $v500;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $v501;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $v502;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $v503;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $v506;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $solde;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatebareme(): ?\DateTimeInterface
    {
        return $this->datebareme;
    }

    public function setDatebareme(\DateTimeInterface $datebareme): self
    {
        $this->datebareme = $datebareme;

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

    public function getV500(): ?string
    {
        return $this->v500;
    }

    public function setV500(string $v500): self
    {
        $this->v500 = $v500;

        return $this;
    }

    public function getV501(): ?string
    {
        return $this->v501;
    }

    public function setV501(string $v501): self
    {
        $this->v501 = $v501;

        return $this;
    }

    public function getV502(): ?string
    {
        return $this->v502;
    }

    public function setV502(string $v502): self
    {
        $this->v502 = $v502;

        return $this;
    }

    public function getV503(): ?string
    {
        return $this->v503;
    }

    public function setV503(string $v503): self
    {
        $this->v503 = $v503;

        return $this;
    }

    public function getV506(): ?string
    {
        return $this->v506;
    }

    public function setV506(string $v506): self
    {
        $this->v506 = $v506;

        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(string $solde): self
    {
        $this->solde = $solde;

        return $this;
    }
}
