<?php

namespace App\Controller;

use DateTime;
use Helpers\NumberUtils;

use App\Entity\Bareme;
use App\Entity\PersonnelDetails;
use App\Entity\BaremePersonnel;
use App\Entity\BaremePersonnelTemporaire;
use App\Entity\Contrat;
use App\Entity\Directions;
use App\Entity\ContratChargeSocialDetails;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\DirectionsRepository;
use App\Repository\BaremePersonnelRepository;
use App\Repository\BaremePersonnelTemporaireRepository;
use App\Repository\ContratChargeSocialDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class SalaireController extends AbstractController
{
    /**
     * @Route("/salaire", name="app_salaire")
     */
    public function index(): Response
    {
        return $this->render('salaire/index.html.twig', [
            'controller_name' => 'SalaireController',
            'number' => 200
        ]);
    }

    /**
     * @Route("/salaire/general", methods={"GET"})
     */
    public function etatgeneral(RequestStack $requestStack, ManagerRegistry $doctrine): Response
    {
        // $conn = $this->getEntityManager()->getConnection();

        // $sql = '
        //     SELECT * FROM product p
        //     WHERE p.price > :price
        //     ORDER BY p.price ASC
        //     ';
        // $stmt = $conn->prepare($sql);
        // $resultSet = $stmt->executeQuery(['price' => $price]);

        // // returns an array of arrays (i.e. a raw data set)
        // $results = $resultSet->fetchAllAssociative();
        $contrats = $doctrine->getRepository(Contrat::class)->findAll();
        $directions = $doctrine->getRepository(Directions::class)->findAll();
        return $this->render('salaire/etat_gle.html.twig', [
            'current_page' => 'Etat general',
            'controller_name' => 'SalaireController:etat_general',
            'directions' => $directions,
            'contrats' => $contrats,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/salaire/direction/", methods={"GET"})
     */
    public function etatdirection(Request $requestStack, BaremePersonnelRepository $rep, BaremePersonnelTemporaireRepository $trep,ContratChargeSocialDetailsRepository $ccsrep): Response
    {
        date_default_timezone_set('Africa/Nairobi');
        
        $date = (isset($_GET['date']) && strlen($_GET['date']) > 0 && strtotime($_GET['date'])) ? $_GET['date'] : date('Y-m-d');
        
        $id = isset($_GET['direction']) ? $_GET['direction'] : -1;
        $contrat_id = isset($_GET['contrat']) ? $_GET['contrat'] : -1;
        $personnel_id = isset($_GET['personnel_id']) ? $_GET['personnel_id'] : -1;
        $results = [];  

        if($personnel_id != -1 ){
            $results = $rep->findByPersonnelId($personnel_id, $date);
        } else if($id != -1 || $contrat_id != -1){
            $results = $rep->findByDirection($id, $date, $contrat_id);
        } else {
            $results = $rep->findByDatebareme($date);
        }
        $date = date_format(date_create($date), "M Y");
        $pdf = new \FPDF('P','mm','A4');
        
        if(count($results) <= 0){
            $pdf->AddPage();
            $error = "Bareme inexistant";
            $pdf->SetFont('Arial','B',32);
            $w = $pdf->GetStringWidth($error);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,3,$error,0,1,'C',false);
        }

        foreach($results as $personnel){

            if($personnel["contrat"] == "Temporaire" || $personnel["contrat"] == "EMO"){
                $p = $trep->findByPersonnelId($personnel["personnel_id"], $date);
                $this->bulletinEMO($pdf, $p, $date);
            } else {
                $p = new BaremePersonnel(
                    $personnel["id"],
                    $personnel["categorie"],
                    $personnel["indice"],
                    $personnel["v500"],
                    $personnel["v501"],
                    $personnel["v502"],
                    $personnel["v503"],
                    $personnel["v506"],
                    $personnel["solde"],
                    $personnel["nom"],
                    $personnel["direction_id"],
                    $personnel["direction"],
                    $personnel["contrat_id"],
                    $personnel["contrat"]
                );
                $p->setPrenom($personnel["prenom"]);
                $p->setChargesSociales($ccsrep->findBy([
                    'contrat_id' => $p->getContratId()
                ]));
                
                $pdf->AddPage();
                $this->setPdfHeader($pdf);

                $pdf->SetFont('Arial','',10);
                // Header
                $nom = iconv('utf-8', 'cp1252', $personnel["nom"]." ".$personnel["prenom"]);
                $header = [$nom, 'MLE', 'INDICE', 'IMPUTATION', 'ENFANT(S)', 'Mois'];
                $w = array($pdf->GetStringWidth($personnel["nom"]) + 25, 25, 25, 25, 25, 25);
                for($i=0;$i<count($header);$i++)
                    $pdf->Cell($w[$i],7,$header[$i],0,0,'C');
                $pdf->Ln();

                $contrat = sprintf("%s CAT %s",$personnel["contrat"], $personnel["categorie"]);
                $personnel_info = [$contrat, 'MLE' ,$personnel["indice"]." FOP", '60 1 2', '0', $date];
                for($i=0;$i<count($personnel_info);$i++)
                    $pdf->Cell($w[$i],7,$personnel_info[$i],0,0,'C');
                $pdf->Ln();
                $pdf->Ln();

                
                $pdf->Cell(65,6,"IMPOSABLES",0,1,'L');
                $pdf->Cell(70,7,"APPOINTEMENT/SALAIRE DE BASE (500)",0,0,'L');
                $pdf->Cell(25,7, number_format($p->getV500(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format($p->getV500(), 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"COMPLEMENT DE SOLDE (501)",0,0,'L');
                $pdf->Cell(25,7, number_format($p->getV501(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format($p->getV501(), 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"COMPLEMENT DE SOLDE (502)",0,0,'L');
                $pdf->Cell(25,7, number_format($p->getV502(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format($p->getV502(), 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"COMPLEMENT DE SOLDE (503)",0,0,'L');
                $pdf->Cell(25,7, number_format($p->getV503(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format($p->getV503(), 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"COMPLEMENT DE SOLDE (506)",0,0,'L');
                $pdf->Cell(25,7, number_format($p->getV506(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format($p->getV506(), 2, ".", ","),0,1,'R');

                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(70,7,"",0,0,'L');
                $pdf->Cell(25,7, "SOUS TOTAL 01",0,0,'R');
                $pdf->Cell(25,7, number_format($p->getSousTotal01(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, "",0,0,'R');

                // if($p->getContrat() == "ECD"){
                //     $pdf->Cell(25,7, "CNAPS 13%",0,1,'R');
                //     $pdf->SetFont('Arial','',10);
                //     $pdf->Cell(70,7,"RETENUE",0,0,'L');
                //     $pdf->Cell(25,7, "CNAPS 1%",0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getCNAPS(), 2, ".", ","),0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getCNAPS(), 2, ".", ","),0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getSousTotal01() * 0.13, 2, ".", ","),0,1,'R');
                // }else if ($p->getContrat() == "Fonctionnaire") {    
                //     $pdf->Cell(25,7, "CRCM 19%",0,1,'R');
                //     $pdf->SetFont('Arial','',10);
                //     $pdf->Cell(70,7,"RETENUE",0,0,'L'); 
                //     $pdf->Cell(25,7, "CRCM 5%",0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getCRCM(), 2, ".", ","),0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getCRCM(), 2, ".", ","),0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getSousTotal01() * 0.19, 2, ".", ","),0,1,'R');
                // }else{     
                //     $pdf->Cell(25,7, "CPR 19%",0,1,'R');
                //     $pdf->SetFont('Arial','',10);
                //     $pdf->Cell(70,7,"RETENUE",0,0,'L');
                //     $pdf->Cell(25,7, "CPR 5%",0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getCRCM(), 2, ".", ","),0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getCPR(), 2, ".", ","),0,0,'R');
                //     $pdf->Cell(25,7, number_format($p->getSousTotal01() * 0.19, 2, ".", ","),0,1,'R');
                // }

                $chsociales = $p->getChargesSociales();
                foreach($chsociales as $chsociale){
                    $pdf->Cell(25,7, sprintf("%s %d %s", $chsociale->getDesignation(), $chsociale->getPartPatronale(), "%"),0,1,'R');
                    $pdf->SetFont('Arial','',10);
                    $pdf->Cell(70,7,"RETENUE",0,0,'L'); 
                    $pdf->Cell(25,7, sprintf("%s %d %s", $chsociale->getDesignation(), $chsociale->getPartSalariale(), "%"),0,0,'R');
                    $pdf->Cell(25,7, number_format($p->getCRCM(), 2, ".", ","),0,0,'R');
                    $pdf->Cell(25,7, number_format($p->getCRCM(), 2, ".", ","),0,0,'R');
                    $pdf->Cell(25,7, number_format($p->getSousTotal01() * 0.19, 2, ".", ","),0,1,'R');
                }

                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(70,7,"",0,0,'L');
                $pdf->Cell(25,7, "SOUS TOTAL 02",0,0,'R');
                $pdf->Cell(25,7, number_format($p->getSousTotal02(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, "",0,0,'R');
                $pdf->Cell(25,7, "",0,1,'R');
                
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(70,7,"COMPLEMENT DE SOLDE (505)",0,0,'L');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"Indemnite de residence",0,0,'L');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"Indemnite de logement",0,0,'L');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
                
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(70,7,"",0,0,'L');
                $pdf->Cell(25,7, "SOUS TOTAL 03",0,0,'R');
                $pdf->Cell(25,7, number_format($p->getSousTotal03(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, "",0,0,'R');
                $pdf->Cell(25,7, "",0,1,'R');
                
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(70,7,"NET IMPOSABLE",0,0,'L');
                $pdf->Cell(25,7, number_format($p->getSousTotal03(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format($p->getSousTotal03(), 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"I.R.S.A CORRESPONDANT",0,0,'L');
                $pdf->Cell(25,7, number_format($p->getIRSA(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format($p->getIRSA(), 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"REDUCTION D'IMPOT",0,1,'L');
                $pdf->Cell(70,7,"   POUR ENFANT A CHARGE",0,0,'L');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"RETENUE DE LOGEMENT",0,0,'L');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"PENSION ALIMENTAIRE 01",0,0,'L');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
                $pdf->Cell(70,7,"PENSION ALIMENTAIRE 02",0,0,'L');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
                
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(70,7,"",0,0,'L');
                $pdf->Cell(25,7, "TOTAL ",0,0,'R');
                $pdf->Cell(25,7, number_format($p->getSousTotal03(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, number_format($p->getIRSA(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, "",0,1,'R');
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(70,7,"",0,0,'L');
                $pdf->Cell(25,7, "NET A PAYER EN AR.",0,0,'R');
                $pdf->Cell(25,7, number_format($p->getNetAPayer(), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, "",0,0,'R');
                $pdf->Cell(25,7, "",0,1,'R');
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(70,7,"",0,0,'L');
                $pdf->Cell(25,7, "Arrondi ",0,0,'R');
                $pdf->Cell(25,7, number_format(round($p->getNetAPayer()), 2, ".", ","),0,0,'R');
                $pdf->Cell(25,7, "",0,0,'R');
                $pdf->Cell(25,7, "",0,1,'R');

                $pdf->Ln();
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(70,7,"Mahajanga, le ".date("d/m/Y") ,0,0,'L');
                $pdf->Cell(25,7, "",0,0,'R');
                $pdf->Cell(25,7, "",0,0,'R');
                $pdf->Cell(25,7, iconv('UTF-8', 'windows-1252', "Certifié le Service Fait,"),0,0,'C');
                $pdf->Cell(25,7, "",0,1,'R');
                
                $pdf->Cell(70,7,"",0,0,'L');
                $pdf->Cell(25,7, "",0,0,'R');
                $pdf->Cell(25,7, "",0,0,'R');
                $pdf->Cell(25,7, "LE GESTIONNAIRE D'ACTIVITE",0,0,'C');
                $pdf->Cell(25,7, "",0,1,'R');

                // // Data
                // foreach($data as $row)
                // {
                //     $this->Cell($w[0],6,$row[0],'LR');
                //     $this->Cell($w[1],6,$row[1],'LR');
                //     $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
                //     $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
                //     $this->Ln();
                // }
                // // Closing line
                // $this->Cell(array_sum($w),0,'','T');
                
                    
            }
        }

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }

    function bulletinEMO($pdf, $ps, $date){
        $datedebut = $datefin = ""; 
        foreach($ps as $p){
            $personnel = new BaremePersonnelTemporaire(
                $p["id"],
                $p["indice"],
                $p["taux_par_heure"],
                $p["personnel_id"],
                $p["nom"],
                $p["prenom"],
                $p["direction_id"],
                $p["direction"],
                $p["duree_contrat_temporaire"],
                $p["heure_par_jour_temporaire"],
            );
            $datedebut = $p["date_debut_contrat"];
            $datefin = $p["date_fin_contrat_temporaire"];
        
            $horaire = intval($personnel->getDureeContratTemporaire()) *  intval($personnel->getHeureParJourTemporaire()) ;
            
            $pdf->AddPage();
            $this->setPdfHeader($pdf, false); 


            $title = "ETAT DE COMPTE DE SALAIRE D'UN AGENT TEMPORAIRE";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,3,$title,0,1,'C',false);

            $title = "A LA COMMUNE URBAINE DE MAHAJANGA I";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,5,$title,0,1,'C',false);

            
            $pdf->SetFont('Arial','',10);
            // Header
            $nom = iconv('utf-8', 'cp1252', $personnel->getNom()." ".$personnel->getPrenom());
            $header = ['PERIODE', 'NBRE/JOURS/TRAVAIL', 'NBRE/HEURES/JOURS', 'TOT-HEURES'];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell(50,7,$header[$i],0,0,'C');
            $pdf->Ln();
            $header = [sprintf('%s au %s',$datedebut ,$datefin), $personnel->getDureeContratTemporaire().' Jours', $personnel->getHeureParJourTemporaire().' Heures', $horaire];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell(50,7,$header[$i],0,0,'C');
            $pdf->Ln();
            $header = ['Nom et prenom', 'Indice', 'Imputation', 'TAUX/HORS', 'MOIS'];
            $w = [ $pdf->GetStringWidth($nom) + 25 ,25,25,25,25];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,'C');
            $pdf->Ln();
            $header = [$nom,  $personnel->getIndice(), '6012', $personnel->getTauxParHeure(), $date];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,'C');
            $pdf->Ln();

            $pdf->Cell(65,7,"",0,1,'L');
            $pdf->Cell(70,7,"SALAIRE DE BASE ",0,0,'L');
            $pdf->Cell(70,7, number_format($personnel->getSalaireDeBase($horaire), 2, ".", ","),0,1,'R');
            $pdf->Cell(70,7,"MAJORATION DE 14 % ",0,0,'L');
            $pdf->Cell(70,7, number_format($personnel->getMajoration($horaire), 2, ".", ","),0,1,'R');

            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(35,7,"TOTAL SALAIRE BRUT",0,0,'L');
            $pdf->Cell(35,7, number_format($personnel->getSalaireBrut($horaire), 2, ".", ","),0,1,'R');
            
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"Montant imposable",0,0,'L');
            $pdf->Cell(70,7, number_format($personnel->getSalaireBrut($horaire), 2, ".", ","),0,1,'R');
            $pdf->Cell(70,7,"IRSA",0,0,'L');
            $pdf->Cell(70,7, number_format($personnel->getIRSA($personnel->getSalaireBrut($horaire)), 2, ".", ","),0,1,'R');

            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(35,7,"Net a payer",0,0,'L');
            $pdf->Cell(35,7, number_format($personnel->getNetaPayer($horaire), 2, ".", ","),0,1,'R');

            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(35,7,"Arrondi a",0,0,'L');
            $pdf->Cell(35,7, number_format(round($personnel->getNetaPayer($horaire)), 2, ".", ","),0,1,'R');

            $pdf->Ln();
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"Mahajanga, le ".date("d/m/Y") ,0,0,'L');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, iconv('UTF-8', 'windows-1252', "Certifié le Service Fait,"),0,0,'C');
            $pdf->Cell(25,7, "",0,1,'R');
            
            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "LE GESTIONNAIRE D'ACTIVITE",0,0,'C');
            $pdf->Cell(25,7, "",0,1,'R');

            // Line break
            $pdf->Ln(10);
        }
    }

    function setPdfHeader($pdf, $show_cum = true){         
        // Arial bold 15
        $title = "Commune";
        $pdf->SetFont('Arial','',8);
        // Calculate width of title and position
        $w = $pdf->GetStringWidth($title);
        // Title
        if ($show_cum) $pdf->Cell($w,3,$title,0,0,'C',false);

        $title = "REPOBLIKAN' I MADAGASIKARA";
        $pdf->SetFont('Arial','',12);
        $w = $pdf->GetStringWidth($title);
        $pdf->SetX((210-$w)/2);
        $pdf->Cell($w,3,$title,0,1,'C',false);
        
        $title = "Urbaine";
        $pdf->SetFont('Arial','',8);
        $w = $pdf->GetStringWidth($title);
        if ($show_cum) $pdf->Cell($w,5,$title,0,0,'C',false);

        $title = "Fitiavana - Tanindrazana - Fandrosoana";
        $pdf->SetFont('Arial','',10);
        $w = $pdf->GetStringWidth($title);
        $pdf->SetX((210-$w)/2);
        $pdf->Cell($w,5,$title,0,1,'C',false);

        $title = "MAHAJANGA";
        $pdf->SetFont('Arial','',8);
        $w = $pdf->GetStringWidth($title);
        if ($show_cum) $pdf->Cell($w,5,$title,0,0,'C',false);

        
        // Line break
        $pdf->Ln(10);
    }

    /**
     * @Route("/salaire/statistiques/contrat", methods={"GET"})
     */
    public function etatsalaireparcontratstats(Request $requestStack, ManagerRegistry $doctrine): Response
    {
        date_default_timezone_set('Africa/Nairobi');
        
        $date = (isset($_GET['date']) && strlen($_GET['date']) > 0 && strtotime($_GET['date'])) ? $_GET['date'] : date('Y-m-d');
        $results = [];
        $p = [];
        $results = $doctrine->getRepository(BaremePersonnel::class)->findByDatebareme($date);
        $contrats = $doctrine->getRepository(Contrat::class)->findAll();

        $stats = [
            "date" => $date,
            "stat" => []
        ];

        foreach($results as $personnel){
            $p[] = new BaremePersonnel(
                $personnel["id"],
                $personnel["categorie"],
                $personnel["indice"],
                $personnel["v500"],
                $personnel["v501"],
                $personnel["v502"],
                $personnel["v503"],
                $personnel["v506"],
                $personnel["solde"],
                $personnel["nom"],
                $personnel["direction_id"],
                $personnel["direction"],
                $personnel["contrat_id"],
                $personnel["contrat"]
            );
        }
        $irsa_total = 0; $net_total = 0; $count_total = 0; $cnaps_total = 0;
        foreach($contrats as $contrat){
            $sum = 0;
            $count = 0;
            $irsa = 0;
            $cnaps = 0;
            
            foreach($p as $personnel){
                $personnel->setChargesSociales($doctrine->getRepository(ContratChargeSocialDetails::class)->findBy([
                    'contrat_id' => $personnel->getContratId()
                ]));
                if($personnel->getContratId() == $contrat->getId()){
                    $sum += $personnel->getNetAPayer();
                    $cs = $personnel->getChargesSociales();
                    $irsa += $personnel->getIRSA();
                    $cnaps += $personnel->getChargesSocial();
                    $count += 1;
                }
            }
            $stats['stat'][] = [
                "contrat" => $contrat->getDesignation(),
                "sum" => $sum,
                "count"=> $count,
                "cnaps"=> $cnaps,
                "irsa" => $irsa
            ];
            $irsa_total += $irsa;
            $net_total += $sum; 
            $count_total += $count;
            $cnaps_total += $cnaps;
        }
        $stats['total'] = [
            "irsa_total" => $irsa_total,
            "net_total" => $net_total,
            "cnaps_total" => $cnaps_total,
            "count_total" => $count_total
        ];
        // dd($stats);

        return $this->render('salaire/statistiques_contrat.html.twig', [
            'current_page' => 'Etat de salaire',
            'controller_name' => 'SalaireController:Statistiques',
            'stats' => $stats,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/salaire/statistiques/contrat/annuel", methods={"GET"})
     */
    public function etatsalaireparcontratstatsannuel(Request $requestStack, ManagerRegistry $doctrine): Response
    {
        date_default_timezone_set('Africa/Nairobi');
        
        $annee = (isset($_GET['year']) && strlen($_GET['year']) > 0 && strtotime($_GET['year'])) ? $_GET['year'] : date('Y');
        $contrats = $doctrine->getRepository(Contrat::class)->findAll();
        $stats_annuel = [
            "annee" => $annee
        ];
        $annuel = [];
        $mensuel = [];

        for($i = 1; $i<= 12; $i++){
            $year = $annee."-".$i."-31";
            $temp = $doctrine->getRepository(BaremePersonnel::class)->findByDatebareme($year);
            $annuel[$i] = [
                "mois" => $year,
                "bareme" => $temp
            ];
        }
        // dd($annuel);
        $sum_total = 0; $irsa_total = 0; $count_total = 0; $cnaps_total = 0;
        foreach($contrats as $contrat){
            $sum = 0;
            $count = [];
            $irsa = 0;
            $cnaps = 0;
            
            for($i = 1; $i<= 12; $i++){
                $sum_month = 0;
                foreach($annuel[$i]["bareme"] as $personnel){
                    $p = new BaremePersonnel(
                        $personnel["id"],
                        $personnel["categorie"],
                        $personnel["indice"],
                        $personnel["v500"],
                        $personnel["v501"],
                        $personnel["v502"],
                        $personnel["v503"],
                        $personnel["v506"],
                        $personnel["solde"],
                        $personnel["nom"],
                        $personnel["direction_id"],
                        $personnel["direction"],
                        $personnel["contrat_id"],
                        $personnel["contrat"]
                    );
                    $p->setPersonnelId($personnel["personnel_id"]);
                    $p->setChargesSociales($doctrine->getRepository(ContratChargeSocialDetails::class)->findBy([
                        'contrat_id' => $p->getContratId()
                    ]));
                    if($p->getContratId() == $contrat->getId() && $p->getContrat() != "EMO"){
                        $sum += $p->getNetAPayer();
                        $irsa += $p->getIRSA();
                        $cnaps += $p->getChargesSocial();
                        $count[$p->getPersonnelId()] = $p->getPersonnelId();
                    }
                    if(($p->getContratId() == $contrat->getId()) && ($annee."-".$i."-31" == $annuel[$i]["mois"])){
                        $sum_month += $p->getNetAPayer();
                    }
                }
                $mensuel[$annee."-".$i][] = [
                    "mois" => $annee."-".$i."-31",
                    "contrat" => $contrat->getDesignation(),
                    "sum" => $sum_month
                ];
            }
            $stats_annuel['annuel'][] = [
                "contrat" => $contrat->getDesignation(),
                "sum" => $sum,
                "count" => count($count),
                "cnaps" => $cnaps,
                "irsa" => $irsa
            ];
            $count_total += count($count);
            $sum_total += $sum;
            $irsa_total += $irsa;
            $cnaps_total += $cnaps;
            $stats_annuel['mensuel'] = $mensuel;
        }
        $stats_annuel['total'] = [
            "net_total" => $sum_total,
            "count_total" => $count_total,
            "cnaps_total" => $cnaps_total,
            "irsa_total" => $irsa_total,
        ];
        // dd($stats_annuel);

        return $this->render('salaire/statistiques_contrat_annuel.html.twig', [
            'current_page' => 'Etat de salaire',
            'controller_name' => 'SalaireController:Statistiques',
            'stats' => $stats_annuel,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/salaire/statistiques/direction", methods={"GET"})
     */
    public function etatsalairepardirectionstats(Request $requestStack, ManagerRegistry $doctrine): Response
    {
        date_default_timezone_set('Africa/Nairobi');
        
        $date = (isset($_GET['date']) && strlen($_GET['date']) > 0 && strtotime($_GET['date'])) ? $_GET['date'] : date('Y-m-d');
        $results = [];
        $p = [];
        $results = $doctrine->getRepository(BaremePersonnel::class)->findByDatebareme($date);
        $directions = $doctrine->getRepository(Directions::class)->findAll();

        $stats = [
            "date" => $date,
            "stat" => []
        ];

        foreach($results as $personnel){
            $p[] = new BaremePersonnel(
                $personnel["id"],
                $personnel["categorie"],
                $personnel["indice"],
                $personnel["v500"],
                $personnel["v501"],
                $personnel["v502"],
                $personnel["v503"],
                $personnel["v506"],
                $personnel["solde"],
                $personnel["nom"],
                $personnel["direction_id"],
                $personnel["direction"],
                $personnel["contrat_id"],
                $personnel["contrat"]
            );
        }
        $irsa_total = 0; $net_total = 0; $count_total = 0; $cnaps_total = 0;
        foreach($directions as $direction){
            $sum = 0;
            $count = 0;
            $irsa = 0;
            $cnaps = 0;
            
            foreach($p as $personnel){
                $personnel->setChargesSociales($doctrine->getRepository(ContratChargeSocialDetails::class)->findBy([
                    'contrat_id' => $personnel->getContratId()
                ]));
                if($personnel->getDirectionId() == $direction->getId()){
                    $sum += $personnel->getNetAPayer();
                    $irsa += $personnel->getIRSA();
                    $cnaps += $personnel->getChargesSocial();
                    $count += 1;
                }
            }
            $stats['stat'][] = [
                "direction" => $direction->getDesignation(),
                "sum" => $sum,
                "count"=> $count,
                "cnaps" => $cnaps,
                "irsa" => $irsa
            ];
            $irsa_total += $irsa;
            $net_total += $sum; 
            $count_total += $count;
            $cnaps_total += $cnaps;
        }
        $stats['total'] = [
            "irsa_total" => $irsa_total,
            "net_total" => $net_total, 
            "cnaps_total" => $cnaps_total,
            "count_total" => $count_total
        ];

        return $this->render('salaire/statistiques_direction.html.twig', [
            'current_page' => 'Etat de salaire',
            'controller_name' => 'SalaireController:Statistiques',
            'stats' => $stats,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/salaire/indemnite", methods={"GET"})
     */
    public function indemniteform(Request $requestStack): Response
    { 
        return $this->render('salaire/indemnite_form.html.twig', [
            'current_page' => 'Indemnite',
            'controller_name' => 'SalaireController:Indemnite',
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    /**
     * @Route("/salaire/indemnites", methods={"POST"})
     */
    public function indemnitePDF(Request $requestStack, ManagerRegistry $doctrine): Response
    { 
        date_default_timezone_set('Africa/Nairobi');
        $personnel_id = (isset($_POST['personnel_id'])) ? $_POST['personnel_id'] : -1;
        $date = (isset($_POST['date'])) ? $_POST['date'] : date('Y-m-d');
        $date = date_format(date_create($date), "M Y");
        $resp = (isset($_POST['resp'])) ? $_POST['resp'] : 0;
        $repr = (isset($_POST['repr'])) ? $_POST['repr'] : 0;
        $alim = (isset($_POST['alim'])) ? $_POST['alim'] : 0;

        $personnels = [];
        if ($personnel_id == -1)
            $personnels = $doctrine->getRepository(PersonnelDetails::class)->findAll();
        else
            $personnels[] = $doctrine->getRepository(PersonnelDetails::class)->find($personnel_id);

        $pdf = new \FPDF('P','mm','A4');

        $this->createIndemnitePDF($pdf, $personnels, $date, $resp, $repr, $alim);

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'
        ));
    }

    private function createIndemnitePDF($pdf, $personnels, $mois, $resp, $repr, $alim){
        $repr = number_format(floatval($repr), 2, ".", ",");
        $resp = number_format(floatval($resp), 2, ".", ",");
        $alim = number_format(floatval($alim), 2, ".", ",");

        foreach($personnels as $personnel){

            $pdf->AddPage();

            // INDEMNITE DE RESPONSABILITE DE CAISSE TRESOR
            $title = "ETAT INDEMNITE DE RESPONSABILITE CAISSE TRESOR";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,3,$title,0,1,'C',false);

            $title = "ADMNISTRATION ET COORDINATION";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,5,$title,0,1,'C',false);

            $title = "VIREMENT BANCAIRE";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,5,$title,0,1,'C',false);
            $pdf->Cell($w,5,"",0,1,'C',false);

            $nom = $personnel->getNom()." ".$personnel->getPrenom();
            $header = ['Nom et prenom', 'IM', 'COMPTE', 'MOIS'];
            $w = [ $pdf->GetStringWidth($nom) + 65 ,25,25,25];
            $align = ['L', 'C', 'C', 'C'];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,$align[$i]);
            $pdf->Ln();
            $header = [$nom, $personnel->getCIN(), '6031', $mois];
            $w = [ $pdf->GetStringWidth($nom) + 65 ,25,25,25];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,$align[$i]);
            $pdf->Ln();

            $title = "INDEMNITE DE RESPONSABILITE DE CAISSE TRESOR";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell($w,5, $title,0,1,'L',false);
            $title = sprintf("TOTAL PENDANT MOIS %s %s x 1",$mois , $resp);
            $pdf->Cell($w,5, $title,0,0,'L',false);
            $pdf->Cell(25,5, "",0,0,'L',false);
            $pdf->Cell(25,5, $resp,0,1,'C',false);
            $pdf->Cell($w,5, "",0,0,'L',false);
            $pdf->Cell(25,5, "Total",0,0,'L',false);
            $pdf->Cell(25,5, $resp,0,1,'C',false);

            $title = sprintf("Arrete le present etat a la somme de %s ARIARY : ", strtoupper($this->convertNumberToWord($resp)));
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell($w,5, $title,0,1,'L',false);

            $pdf->Ln();
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"Mahajanga, le ".date("d/m/Y") ,0,0,'L');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, iconv('UTF-8', 'windows-1252', "Certifié le Service Fait,"),0,0,'C');
            $pdf->Cell(25,7, "",0,1,'R');
            
            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "LE GESTIONNAIRE D'ACTIVITE",0,0,'C');
            $pdf->Cell(25,7, "",0,1,'R');

            // Line break
            $pdf->Ln(10);
            $pdf->SetY($pdf->GetPageHeight()/2);

            $title = "ETAT INDEMNITE DE RESPRESENTATION";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,5,$title,0,1,'C',false);

            $title = "ADMNISTRATION ET COORDINATION";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,5,$title,0,1,'C',false);

            $nom = $personnel->getNom()." ".$personnel->getPrenom();
            $header = ['Nom et prenom', 'IM', 'COMPTE', 'MOIS'];
            $w = [ $pdf->GetStringWidth($nom) + 65 ,25,25,25];
            $align = ['L', 'C', 'C', 'C'];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,$align[$i]);
            $pdf->Ln();
            $header = [$nom, $personnel->getCIN(), '6031', $mois];
            $w = [ $pdf->GetStringWidth($nom) + 65 ,25,25,25];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,$align[$i]);
            $pdf->Ln();

            // INDEMNITE DE REPRESENTATION
            $title = "INDEMNITE DE REPRESENTATION";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell($w,5, $title,0,1,'L',false);
            $title = sprintf("TOTAL PENDANT MOIS %s %s x 1",$mois , $repr);
            $pdf->Cell($w,5, $title,0,0,'L',false);
            $pdf->Cell(25,5, "",0,0,'L',false);
            $pdf->Cell(25,5, $repr,0,1,'C',false);
            $pdf->Cell($w,5, "",0,0,'L',false);
            $pdf->Cell(25,5, "Total",0,0,'L',false);
            $pdf->Cell(25,5, $repr,0,1,'C',false);

            $title = sprintf("Arrete le present etat a la somme de %s ARIARY : ", strtoupper($this->convertNumberToWord($repr)));
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell($w,5, $title,0,1,'L',false);
            
            $pdf->Ln();
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"Mahajanga, le ".date("d/m/Y") ,0,0,'L');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, iconv('UTF-8', 'windows-1252', "Certifié le Service Fait,"),0,0,'C');
            $pdf->Cell(25,7, "",0,1,'R');
            
            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "LE GESTIONNAIRE D'ACTIVITE",0,0,'C');
            $pdf->Cell(25,7, "",0,1,'R');


            $pdf->AddPage();
            $title = "ETAT (PENSION ALIMENTAIRE)";
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->SetX((210-$w)/2);
            $pdf->Cell($w,3,$title,0,1,'C',false);
            $pdf->Ln();
            $pdf->Ln();
            
            $nom = $personnel->getNom()." ".$personnel->getPrenom();
            $header = ['Nom et prenom', 'CIN', 'COMPTE', 'MOIS'];
            $w = [ $pdf->GetStringWidth($nom) + 65 ,25,25,25];
            $align = ['L', 'C', 'C', 'C'];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,$align[$i]);
            $pdf->Ln();
            $header = [$nom, $personnel->getCIN(), '6031', $mois];
            $w = [ $pdf->GetStringWidth($nom) + 65 ,25,25,25];
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,$align[$i]);
            $pdf->Ln();

            
            $title = sprintf("PENSION ALIMENTAIRE MOIS %s ",$mois);
            $pdf->SetFont('Arial','',12);
            $w = $pdf->GetStringWidth($title);
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell($w,5, $title,0,1,'L',false);
            $title = sprintf("%s x 1 = " , $alim);
            $pdf->Cell($w,5, "",0,0,'R',false);
            $pdf->Cell(25,5, $title,0,0,'R',false);
            $pdf->Cell(25,5, $alim,0,1,'C',false);
            $pdf->Cell($w,5, "",0,0,'L',false);
            $pdf->Cell(25,5, "Total",0,0,'L',false);
            $pdf->Cell(25,5, $alim,0,1,'C',false);

            $title = sprintf("Arrete le present etat a la somme de %s ARIARY : ", strtoupper($this->convertNumberToWord($alim)));
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell($w,5, $title,0,1,'L',false);
            
            $pdf->Ln();
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"Mahajanga, le ".date("d/m/Y") ,0,0,'L');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, iconv('UTF-8', 'windows-1252', "Certifié le Service Fait,"),0,0,'C');
            $pdf->Cell(25,7, "",0,1,'R');
            
            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "LE GESTIONNAIRE D'ACTIVITE",0,0,'C');
            $pdf->Cell(25,7, "",0,1,'R');


            // Line break
            $pdf->Ln(10);
        }
    }

    public function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze',
            'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'
        );
        $list2 = array('', 'dix', 'vingt', 'trente', 'quarente', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt dix', 'cent');
        $list3 = array('', 'mille', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' cent' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }
    
}
