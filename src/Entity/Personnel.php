<?php

namespace App\Entity;

use App\Repository\PersonnelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonnelRepository::class)
 * @ORM\Table(name="personnel")
 */
class Personnel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank
     */
    private $CIN;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $datedenaissance;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $nbenfant;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $sexe_id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $situationmatrimoniale_id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     */
    private $lieudenaissance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDatedenaissance(): ?\DateTimeInterface
    {
        return $this->datedenaissance;
    }

    public function setDatedenaissance(?\DateTimeInterface $datedenaissance): self
    {
        $this->datedenaissance = $datedenaissance;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(?string $CIN): self
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function getNbEnfant(): ?int
    {
        return $this->nbenfant;
    }

    public function setNbEnfant(?int $nbenfant): self
    {
        $this->nbenfant = $nbenfant;

        return $this;
    }

    public function getSexeId(): ?int
    {
        return $this->sexe_id;
    }

    public function setSexeId(int $sexe_id): self
    {
        $this->sexe_id = $sexe_id;

        return $this;
    }

    public function getSituationmatrimonialeId(): ?int
    {
        return $this->situationmatrimoniale_id;
    }

    public function setSituationmatrimonialeId(int $situationmatrimoniale_id): self
    {
        $this->situationmatrimoniale_id = $situationmatrimoniale_id;

        return $this;
    }

    public function getLieudenaissance(): ?string
    {
        return $this->lieudenaissance;
    }

    public function setLieudenaissance(?string $lieudenaissance): self
    {
        $this->lieudenaissance = $lieudenaissance;

        return $this;
    }
}
