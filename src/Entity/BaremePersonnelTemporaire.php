<?php

namespace App\Entity;

use App\Repository\BaremePersonnelTemporaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BaremePersonnelTemporaireRepository::class)
 * @ORM\Table(name="v_bareme_par_personnel_temporaire")
 */
class BaremePersonnelTemporaire
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
    private $indice;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2, nullable=true)
     */
    private $taux_par_heure;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
    * @ORM\Column(type="integer")
    */
    private $personnel_id;
    /**
    * @ORM\Column(type="string", length=255)
    */
    private $nom;

    /**
    * @ORM\Column(type="string", length=50)
    */
    private $Prenom;

    /**
    * @ORM\Column(type="integer")
    */
    private $direction_id;

    /**
    * @ORM\Column(type="string", length=50)
    */
    private $direction;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_debut_contrat;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_fin_contrat_temporaire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree_contrat_temporaire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $heure_par_jour_temporaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
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

    public function getTauxParHeure(): ?string
    {
        return $this->taux_par_heure;
    }

    public function setTauxParHeure(?string $taux_par_heure): self
    {
        $this->taux_par_heure = $taux_par_heure;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(?string $Prenom): self
    {
        $this->Prenom = $Prenom;

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

    
    public function getDateDebutContrat(): ?\DateTimeInterface
    {
        return $this->date_debut_contrat;
    }

    public function setDateDebutContrat(?\DateTimeInterface $date_debut_contrat): self
    {
        $this->date_debut_contrat = $date_debut_contrat;

        return $this;
    }

    public function getDateFinContratTemporaire(): ?\DateTimeInterface
    {
        return $this->date_fin_contrat_temporaire;
    }

    public function setDateFinContratTemporaire(?\DateTimeInterface $date_fin_contrat_temporaire): self
    {
        $this->date_fin_contrat_temporaire = $date_fin_contrat_temporaire;

        return $this;
    }

    public function getDureeContratTemporaire(): ?int
    {
        return $this->duree_contrat_temporaire;
    }

    public function setDureeContratTemporaire(?int $duree_contrat_temporaire): self
    {
        $this->duree_contrat_temporaire = $duree_contrat_temporaire;

        return $this;
    }

    public function getHeureParJourTemporaire(): ?int
    {
        return $this->heure_par_jour_temporaire;
    }

    public function setHeureParJourTemporaire(?int $heure_par_jour_temporaire): self
    {
        $this->heure_par_jour_temporaire = $heure_par_jour_temporaire;

        return $this;
    } 

    public function getSalaireDeBase($heure){
        return $heure * $this->getTauxParHeure();
    }

    public function getMajoration($heure){
        $base = $this->getSalaireDeBase($heure);
        return ($base * 0.14);
    }

    public function getSalaireBrut($heure){
        $base = $this->getSalaireDeBase($heure);
        return $base + ($base * 0.14);
    }

    public function getIRSA($montant)
    {
        // return $this->getSousTotal01() * 0.2;

        $s = $montant;
        // diffirent tranche de calcul
        $tranche1 = $tranche2 = $tranche3 = $tranche4 = 0;
        $irsa = 0;
        $minimum = 3000;
        // si inferieur a 350 000
        // minimum de perception
        if($s < 350000) return $minimum;
        // sinon 
        else {
            
            // 1ere tranche entre 350 et 400
            if(350000<$s && $s <= 400000) $tranche1 = ($s - 350001) * 0.05;
            else if (400000 < $s) $tranche1 = (400000 - 350001) * 0.05;
            
            // 2eme tranche entre 400 et 500
            if(400000<$s && $s <= 500000) $tranche2 = ($s - 400001) * 0.1;
            else if (500000 < $s) $tranche2 = (500000 - 400001) * 0.1;
            
            // 3eme tranche entre 500 et 600
            if(500000<$s && $s <= 600000) $tranche3 = ($s - 500001) * 0.15;
            else if (600000 < $s) $tranche3 = (600000 - 500001) * 0.15;
            
            // 4eme tranche < a 600
            if(600000<$s) $tranche4 = ($s - 600001) * 0.2;
            
            // somme des tranche
            $irsa += ($tranche1 + $tranche2 + $tranche3 + $tranche4); 
            // minimum de perception
            $irsa = ($irsa < $minimum) ? $irsa + $minimum : $irsa;
            return round($irsa);
        }
    }

    public function getNetaPayer($heure){
        $brut = $this->getSalaireBrut($heure);
        return $brut - $this->getIRSA($brut);
    }

    public function __construct($id,$indice,$taux, $personnel_id, $nom, $prenom, $direction_id, $direction, $duree_contrat_temporaire, $heure_par_jour_temporaire)
    {
        $this->setId($id);
        $this->setIndice($indice);
        $this->setTauxParHeure($taux);
        $this->setPersonnelId($personnel_id);
        $this->setNom($nom);
        $this->setPrenom($prenom);
        $this->setDirectionId($direction_id);
        $this->setDirection($direction);
        $this->setDureeContratTemporaire($duree_contrat_temporaire);
        $this->setHeureParJourTemporaire($heure_par_jour_temporaire);
    }

}
