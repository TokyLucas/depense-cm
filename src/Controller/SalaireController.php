<?php

namespace App\Controller;

use App\Entity\BaremePersonnel;
use App\Repository\DirectionsRepository;
use App\Repository\BaremePersonnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function etatgeneral(DirectionsRepository $rep): Response
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

        $results = $rep->findAll();
        return $this->render('salaire/etat_gle.html.twig', [
            'current_page' => 'Etat general',
            'controller_name' => 'SalaireController:etat_general',
            'directions' => $results
        ]);
    }

        /**
     * @Route("/salaire/direction/{id}", methods={"GET"})
     */
    public function etatdirection(BaremePersonnelRepository $rep, int $id): Response
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

        $results = $rep->findByDirection($id);
        $pdf = new \FPDF('P','mm','A4');
        foreach($results as $personnel){

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
                $personnel["direction"]
            );

            $pdf->AddPage();
            $this->setPdfHeader($pdf);

            $pdf->SetFont('Arial','',10);

            // Header
            $header = [$personnel["nom"], 'MLE', 'INDICE', 'IMPUTATION', 'ENFANT(S)', 'Mois'];
            $w = array($pdf->GetStringWidth($personnel["nom"]) + 25, 25, 25, 25, 25, 25);
            for($i=0;$i<count($header);$i++)
                $pdf->Cell($w[$i],7,$header[$i],0,0,'C');
            $pdf->Ln();

            $contrat = sprintf("D CAT %s",$personnel["categorie"]);
            $personnel_info = [$contrat, 'MLE' ,$personnel["indice"]." FOP", '60 1 2', '0', 'Mois'];
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
            $pdf->Cell(25,7, "CNAPS",0,1,'R');

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"RETENUE",0,0,'L');
            $pdf->Cell(25,7, "CNAPS",0,0,'R');
            $pdf->Cell(25,7, "1%",0,0,'R');
            $pdf->Cell(25,7, "",0,1,'R');

            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(25,7, "SOUS TOTAL 02",0,0,'R');
            $pdf->Cell(25,7, number_format($p->getSousTotal01(), 2, ".", ","),0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "CNAPS",0,1,'R');
            
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
            $pdf->Cell(25,7, number_format($p->getSousTotal01(), 2, ".", ","),0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "CNAPS",0,1,'R');
            
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"NET IMPOSABLE",0,0,'L');
            $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
            $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
            $pdf->Cell(70,7,"I.R.S.A CORRESPONDANT",0,0,'L');
            $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,0,'R');
            $pdf->Cell(25,7, number_format(0, 2, ".", ","),0,1,'R');
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
            $pdf->Cell(25,7, number_format($p->getSousTotal01(), 2, ".", ","),0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,1,'R');
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(25,7, "NET A PAYER EN AR.",0,0,'R');
            $pdf->Cell(25,7, number_format($p->getSousTotal01(), 2, ".", ","),0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,1,'R');
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"",0,0,'L');
            $pdf->Cell(25,7, "Arrondi ",0,0,'R');
            $pdf->Cell(25,7, number_format($p->getSousTotal01(), 2, ".", ","),0,0,'R');
            $pdf->Cell(25,7, "",0,0,'R');
            $pdf->Cell(25,7, "",0,1,'R');

            $pdf->Ln();
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,7,"Mahajanga, le",0,0,'L');
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

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }

    function setPdfHeader($pdf){         
        // Arial bold 15
        $title = "Commune";
        $pdf->SetFont('Arial','',8);
        // Calculate width of title and position
        $w = $pdf->GetStringWidth($title);
        // Title
        $pdf->Cell($w,3,$title,0,0,'C',false);

        $title = "REPOBLIKAN' I MADAGASIKARA";
        $pdf->SetFont('Arial','',12);
        $w = $pdf->GetStringWidth($title);
        $pdf->SetX((210-$w)/2);
        $pdf->Cell($w,3,$title,0,1,'C',false);
        
        $title = "Urbaine";
        $pdf->SetFont('Arial','',8);
        $w = $pdf->GetStringWidth($title);
        $pdf->Cell($w,5,$title,0,0,'C',false);

        $title = "Fitiavana - Tanindrazana - Fandrosoana";
        $pdf->SetFont('Arial','',10);
        $w = $pdf->GetStringWidth($title);
        $pdf->SetX((210-$w)/2);
        $pdf->Cell($w,5,$title,0,1,'C',false);

        $title = "MAHAJANGA";
        $pdf->SetFont('Arial','',8);
        $w = $pdf->GetStringWidth($title);
        $pdf->Cell($w,5,$title,0,0,'C',false);

        // Line break
        $pdf->Ln(10);
    }
}
