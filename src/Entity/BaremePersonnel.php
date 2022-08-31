<?php

namespace App\Entity;

use App\Repository\BaremePersonnelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BaremePersonnelRepository::class, readOnly = true)
 * @ORM\Table(name="v_bareme_par_personnel")
 */
class BaremePersonnel
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
     * @ORM\Column(type="string", length=10)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $indice;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $v500;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $v501;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $v502;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $v503;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $v506;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $solde;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $direction_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $direction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function __construct($id,$categorie,$indice,$v500,$v501,$v502,$v503,$v506,$solde,$nom,$direction_id,$direction)
    {
        $this->setId($id);
        $this->setCategorie($categorie);
        $this->setIndice($indice);
        $this->setV500($v500);
        $this->setV501($v501);
        $this->setV502($v502);
        $this->setV503($v503);
        $this->setV506($v506);
        $this->setSolde($solde);
        $this->setNom($nom);
        $this->setDirectionId($direction_id);
        $this->setDirection($direction);
    }

    public function getSousTotal01()
    {
        $soustotal01 = 0;
        $soustotal01 += floatval($this->getV500());
        $soustotal01 += floatval($this->getV501());
        $soustotal01 += floatval($this->getV502());
        $soustotal01 += floatval($this->getV506());

        return $soustotal01;
    }
}
