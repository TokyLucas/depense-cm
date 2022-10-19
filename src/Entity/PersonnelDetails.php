<?php

namespace App\Entity;

use App\Repository\PersonnelDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonnelDetailsRepository::class)
 * @ORM\Table(name="v_personnel_details")
 */
class PersonnelDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     */
    private $datedenaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

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
     * @ORM\Column(type="string", length=15)
     */
    private $direction;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $Contrat;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $Matricule;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $CIN;

    /**
     * @ORM\Column(type="integer")
     */
    private $contrat_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbenfant;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lieudenaissance;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $situationmatrimoniale;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $service;

    /**
     * @ORM\Column(type="integer")
     */
    private $poste_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $date_avant_retraite;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_retraite;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_debut_contrat;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_fin_contrat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree_contrat_restant;

    /**
     * @ORM\Column(type="integer")
     */
    private $alerte_fin_contrat;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDatedenaissance(): ?\DateTimeInterface
    {
        return $this->datedenaissance;
    }

    public function setDatedenaissance(\DateTimeInterface $datedenaissance): self
    {
        $this->datedenaissance = $datedenaissance;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

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

    public function getIndice(): ?string
    {
        return $this->indice;
    }

    public function setIndice(string $indice): self
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

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getContrat(): ?string
    {
        return $this->Contrat;
    }

    public function setContrat(string $Contrat): self
    {
        $this->Contrat = $Contrat;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->Matricule;
    }

    public function setMatricule(string $Matricule): self
    {
        $this->Matricule = $Matricule;

        return $this;
    }

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(string $CIN): self
    {
        $this->CIN = $CIN;

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

    public function getNbEnfant(): ?int
    {
        return $this->nbenfant;
    }

    public function setNbEnfant(int $nbenfant): self
    {
        $this->nbenfant = $nbenfant;

        return $this;
    }

    public function getLieudenaissance(): ?string
    {
        return $this->lieudenaissance;
    }

    public function setLieudenaissance(string $lieudenaissance): self
    {
        $this->lieudenaissance = $lieudenaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getSituationmatrimoniale(): ?string
    {
        return $this->situationmatrimoniale;
    }

    public function setSituationmatrimoniale(string $situationmatrimoniale): self
    {
        $this->situationmatrimoniale = $situationmatrimoniale;

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

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): self
    {
        $this->service = $service;

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

    public function getDateAvantRetraite(): ?int
    {
        return $this->date_avant_retraite;
    }

    public function setDateAvantRetraite(?int $date_avant_retraite): self
    {
        $this->date_avant_retraite = $date_avant_retraite;

        return $this;
    }

    public function getDateRetraite(): ?\DateTimeInterface
    {
        return $this->date_retraite;
    }

    public function setDateRetraite(?\DateTimeInterface $date_retraite): self
    {
        $this->date_retraite = $date_retraite;

        return $this;
    }

    public function getDateDebutContrat(): ?\DateTimeInterface
    {
        return $this->date_debut_contrat;
    }

    public function setDateDebutContrat(?\DateTimeInterface $date_debut_contrat): self
    {
        $this->date_debut_contrat = $date_debut_contrat;

        return $this;
    }

    public function getDateFinContrat(): ?\DateTimeInterface
    {
        return $this->date_fin_contrat;
    }

    public function setDateFinContrat(?\DateTimeInterface $date_fin_contrat): self
    {
        $this->date_fin_contrat = $date_fin_contrat;

        return $this;
    }

    public function getDureeContratRestant(): ?int
    {
        return $this->duree_contrat_restant;
    }

    public function setDureeContratRestant(?int $duree_contrat_restant): self
    {
        $this->duree_contrat_restant = $duree_contrat_restant;

        return $this;
    }

    public function getAlerteFinContrat(): ?int
    {
        return $this->alerte_fin_contrat;
    }

    public function setAlerteFinContrat(int $alerte_fin_contrat): self
    {
        $this->alerte_fin_contrat = $alerte_fin_contrat;

        return $this;
    }
}
