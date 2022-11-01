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

    /**
     * @ORM\Column(type="integer")
     */
    private $contrat_id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $contrat;

    /**
     * @ORM\Column(type="integer")
     */
    private $personnel_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Prenom;

    private $v505;
    private $indemnites;
    private $chargessociales;
    private $bareme_irsa;
    private $interval_irsa;

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

    public function setDatebareme(?\DateTimeInterface $datebareme): self
    {
        $this->datebareme = $datebareme;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
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

    public function getV500(): ?string
    {
        return $this->v500;
    }

    public function setV500(?string $v500): self
    {
        $this->v500 = $v500;

        return $this;
    }

    public function getV501(): ?string
    {
        return $this->v501;
    }

    public function setV501(?string $v501): self
    {
        $this->v501 = $v501;

        return $this;
    }

    public function getV502(): ?string
    {
        return $this->v502;
    }

    public function setV502(?string $v502): self
    {
        $this->v502 = $v502;

        return $this;
    }

    public function getV503(): ?string
    {
        return $this->v503;
    }

    public function setV503(?string $v503): self
    {
        $this->v503 = $v503;

        return $this;
    }

    public function getV506(): ?string
    {
        return $this->v506;
    }

    public function setV506(?string $v506): self
    {
        $this->v506 = $v506;

        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(?string $solde): self
    {
        $this->solde = $solde;

        return $this;
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

    public function getDirectionId(): ?int
    {
        return $this->direction_id;
    }

    public function setDirectionId(?int $direction_id): self
    {
        $this->direction_id = $direction_id;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(?string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getV505(): ?string
    {
        return $this->v505;
    }

    public function setV505(?string $v505): self
    {
        $this->v505 = $v505;

        return $this;
    }

    public function getIndemnite(): ?string
    {
        return $this->indemnite;
    }

    public function setIndemnite(?string $indemnite): self
    {
        $this->indemnite = $indemnite;

        return $this;
    }

    public function getChargesSociales(): ?array
    {
        return $this->chargessociales;
    }

    public function setChargesSociales(?array $chargessociales): self
    {
        $this->chargessociales = $chargessociales;

        return $this;
    }
    
    public function getBaremeIrsa() 
    {
        return $this->bareme_irsa;
    }

    public function setBaremeIrsa($bareme_irsa): self
    {
        $this->bareme_irsa = $bareme_irsa;

        return $this;
    }

    public function getIntervalIrsa(): ?array
    {
        return $this->interval_irsa;
    }

    public function setIntervalIrsa(?array $interval_irsa): self
    {
        $this->interval_irsa = $interval_irsa;

        return $this;
    }

    public function __construct($id,$categorie,$indice,$v500,$v501,$v502,$v503,$v506,$solde,$nom,$direction_id,$direction,$contrat_id,$contrat)
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
        $this->setContratId($contrat_id);
        $this->setContrat($contrat);
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
    
    public function getChargesSocial(){
        $cs = 0;
        $ch = $this->getChargesSociales();
        if($ch != null){
            foreach($ch as $charge){
                $cs += ( $this->getSousTotal01() * ($charge->getPartSalariale() / 100) );
            }
        }
        return $cs;
    }

    public function getCPR(){
        return $this->getSousTotal01() * 0.05;
    }

    public function getCRCM(){
        return $this->getSousTotal01() * 0.05;
    }

    public function getSousTotal02()
    {
        $soustotal02 = $this->getSousTotal01();
        // if($this->getContrat() == "ECD")
        //     $soustotal02 -= $this->getCNAPS();
        // else if($this->getContrat() == "Fonctionnaire")
        //     $soustotal02 -= $this->getCRCM();
        // else
        //     $soustotal02 -= $this->getCPR();
        $ch = $this->getChargesSociales();
        if($ch != null){
            foreach($ch as $charge){
                $soustotal02 -= ( $this->getSousTotal01() * ($charge->getPartSalariale() / 100) );
            }
        }
        return $soustotal02;
    }

    public function getSousTotal03()
    {
        $soustotal03 = $this->getSousTotal02();

        return $soustotal03;
    }


    public function getIRSA(){
        $s = $this->getSousTotal03();
        $bareme = $this->getBaremeIrsa();
        $intervals = $this->getIntervalIrsa();
        $irsa = 0;
        $minimum = ($bareme != null) ? $bareme->getMin() : 0;
        $tranches = [];
        if($intervals != null){
            foreach($intervals as $interval){
                $min = $interval->getMin();
                $max = $interval->getMax();
                $pourcentage = $interval->getPourcentage() / 100;
    
                if($min<$s && $s <= $max){
                    $tranche = ($s - ($min+1)) * $pourcentage;
                    $tranches[] = $tranche;
                }
                else if ($max < $s && $max > 0) {
                    $tranche = ($max - ($min+1)) * $pourcentage;
                    $tranches[] = $tranche;
                }
            }
        }
        foreach($tranches as $tranche) $irsa += $tranche;
        $irsa = ($irsa < $minimum) ? $irsa + $minimum : $irsa;
        return $irsa;
    }

    // public function getIRSA()
    // {
    //     // return $this->getSousTotal01() * 0.2;

    //     $s = $this->getSousTotal03();
    //     // diffirent tranche de calcul
    //     $tranche1 = $tranche2 = $tranche3 = $tranche4 = 0;
    //     $irsa = 0;
    //     $minimum = 3000;
    //     // si inferieur a 350 000
    //     // minimum de perception
    //     if($s < 350000) return $minimum;
    //     // sinon 
    //     else {
            
    //         // 1ere tranche entre 350 et 400
    //         if(350000<$s && $s <= 400000) $tranche1 = ($s - 350001) * 0.05;
    //         else if (400000 < $s) $tranche1 = (400000 - 350001) * 0.05;
            
    //         // 2eme tranche entre 400 et 500
    //         if(400000<$s && $s <= 500000) $tranche2 = ($s - 400001) * 0.1;
    //         else if (500000 < $s) $tranche2 = (500000 - 400001) * 0.1;
            
    //         // 3eme tranche entre 500 et 600
    //         if(500000<$s && $s <= 600000) $tranche3 = ($s - 500001) * 0.15;
    //         else if (600000 < $s) $tranche3 = (600000 - 500001) * 0.15;
            
    //         // 4eme tranche < a 600
    //         if(600000<$s) $tranche4 = ($s - 600001) * 0.2;
            
    //         // somme des tranche
    //         $irsa += ($tranche1 + $tranche2 + $tranche3 + $tranche4); 
    //         // minimum de perception
    //         $irsa = ($irsa < $minimum) ? $irsa + $minimum : $irsa;
    //         return round($irsa);
    //     }
    // }

    public function getNetAPayer()
    {
        return $this->getSousTotal03() - $this->getIRSA();
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

    public function getContrat(): ?string
    {
        return $this->contrat;
    }

    public function setContrat(?string $contrat): self
    {
        $this->contrat = $contrat;

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
}
