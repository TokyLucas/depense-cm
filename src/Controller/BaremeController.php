<?php

namespace App\Controller;

use App\Repository\BaremeRepository;
use App\Entity\Bareme;
use App\Entity\BaremeIrsa;
use App\Entity\IntervalIrsa;
use App\Entity\BaremeEMO;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Form\BaremeFormType;
use App\Form\BaremeIrsaType;
use App\Form\BaremePersoTempType;

class BaremeController extends AbstractController
{
    /**
     * @Route("/bareme", name="app_bareme")
     */
    public function index(): Response
    {
        return $this->render('bareme/index.html.twig', [
            'current_page' => 'Import bareme',
            'controller_name' => 'BaremeController',
        ]);
    }

    /**
     * @Route("/bareme/import", name="app_bareme_import_form")
     */
    public function import_form(Request $request, BaremeRepository $rep, ManagerRegistry $doctrine): Response
    {
        set_time_limit(120);
        $form = $this->createForm(BaremeFormType::class, null);
        $form->handleRequest($request);
        $fileFolder = __DIR__ . '/../../public/uploads/bareme/general';

        if ($form->isSubmitted()) {
            try{

                $file = $form->get('file')->getData();
                if ($file) {
                    $filePathName = md5(uniqid()) . $file->getClientOriginalName();
                    try {
                        $file->move($fileFolder, $filePathName);
                    } catch (FileException $e) {
                        dd($e);
                    }
    
                    $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file 
                    $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line 
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
                    //dd($sheetData);
    
                    $entityManager = $this->getDoctrine()->getManager(); 
    
                    set_time_limit(36000);
                    $count = 0;
                    foreach ($sheetData as $Row) 
                    { 
                        $datebareme = $Row['A'];
                        $categorie = (int) $Row['B']; 
                        $indice = intval(str_replace(",", "", $Row['C']));
                        $v500 = (double) str_replace(",", "", $Row['D']); 
                        $v501 = (double) str_replace(",", "", $Row['E']); 
                        $v502 = (double) str_replace(",", "", $Row['F']);
                        $v503 = (double) str_replace(",", "", $Row['G']); 
                        $v506 = (double) str_replace(",", "", $Row['H']);  
                        $solde = (double) str_replace(",", "", $Row['I']);
     
                        $bareme = new Bareme(); 
                        $datebareme = \DateTime::createFromFormat("d/m/Y H:i:s",$datebareme);
                        $bareme->setDatebareme($datebareme);           
                        $bareme->setCategorie($categorie);
                        $bareme->setIndice($indice);
                        $bareme->setV500($v500);
                        $bareme->setV501($v501);
                        $bareme->setV502($v502);
                        $bareme->setV503($v503);
                        $bareme->setV506($v506);
                        $bareme->setSolde($solde);
    
                        $rep->add($bareme, false);
    
                        if ($count % 100 === 0) {
                            $rep->flush();
                            $rep->clear();
                        }
                        $count++;
                    }
                    $rep->flush();
                    $rep->clear();
                    set_time_limit(120);
                }
            } catch (Exception $e) {
                return $this->redirectToRoute('app_bareme_import_form', [
                    "e" => $e->getMessage(),
                ]);
            }
            
            $this->addFlash('success', 'Bareme général importer');
            return $this->redirectToRoute('app_bareme_import_form');
        }

        $form_irsa = $this->createForm(BaremeIrsaType::class, null);
        $form_irsa->handleRequest($request);
        $fileFolder = __DIR__ . '/../../public/uploads/bareme/irsa/';
        if ($form_irsa->isSubmitted() && $form_irsa->isValid()) {
            try{

                $file = $form_irsa->get('file')->getData();
                if ($file) {
                    $filePathName = md5(uniqid()) . $file->getClientOriginalName();
                    try {
                        $file->move($fileFolder, $filePathName);
                    } catch (FileException $e) {
                        dd($e);
                    }
    
                    $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file 
                    $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line 
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
                    //dd($sheetData);
    
                    $entityManager = $this->getDoctrine()->getManager(); 
    
                    set_time_limit(36000);
                    $count = 0;
                    $i = [];
                    
                    $bareme = new BaremeIrsa();
                    foreach($sheetData as $Row){
                        $date = $Row['A'];
                        $date = \DateTime::createFromFormat("d/m/Y H:i:s",$date);
                        $min = (double) str_replace(",", "", $Row['B']);
                        $bareme->setDate($date);
                        $bareme->setMin($min);
                        $doctrine->getRepository(BaremeIrsa::class)->add($bareme, true);
                        break;
                    }
                    foreach ($sheetData as $Row) 
                    { 
                        $basemin = (double) str_replace(",", "", $Row['C']);
                        $basemax = (double) str_replace(",", "", $Row['D']); 
                        $pourcentage = (double) str_replace(",", "", $Row['E']);
     
                        $interval = new IntervalIrsa();
                        $interval
                            ->setBaremeId($bareme->getId())
                            ->setMin($basemin)
                            ->setMax($basemax)
                            ->setPourcentage($pourcentage);
                        $i[] = $interval;
                        $doctrine->getRepository(IntervalIrsa::class)->add($interval, true);   
                    }
                    set_time_limit(120);
                }
            } catch (Exception $e) {
                return $this->redirectToRoute('app_bareme_import_form', [
                    "e" => $e->getMessage(),
                ]);
            }
            
            $this->addFlash('success', 'Bareme pour IRSA importer');
            return $this->redirectToRoute('app_bareme_import_form');
        }

        $form_temp = $this->createForm(BaremePersoTempType::class, null);
        $form_temp->handleRequest($request);
        $fileFolder = __DIR__ . '/../../public/uploads/bareme/irsa/';
        if ($form_temp->isSubmitted() && $form_temp->isValid()) {
            try{

                $file = $form_temp->get('file')->getData();
                if ($file) {
                    $filePathName = md5(uniqid()) . $file->getClientOriginalName();
                    try {
                        $file->move($fileFolder, $filePathName);
                    } catch (FileException $e) {
                        dd($e);
                    }
    
                    $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file 
                    $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line 
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
                    //dd($sheetData);
    
                    $entityManager = $this->getDoctrine()->getManager(); 
    
                    set_time_limit(36000);
                    $count = 0;
                    
                    $count = 0;
                    $rep = $doctrine->getRepository(BaremeEMO::class);
                    foreach ($sheetData as $Row) 
                    { 
                        $indice = $Row['A'];
                        $taux = (double) str_replace(",", "", $Row['B']);
                        $date = $Row['C'];
                        $date = \DateTime::createFromFormat("d/m/Y H:i:s",$date);
                        $bareme = new BaremeEMO();
                        $bareme
                            ->setIndice($indice)
                            ->setTauxParHeure($taux)
                            ->setDate($date)
                        ;
                        $rep->add($bareme, true);   
                        if ($count % 100 === 0) {
                            $rep->flush();
                            $rep->clear();
                        }
                        $count++;
                    }
                    $rep->flush();
                    $rep->clear();
                    set_time_limit(120);
                }
            } catch (Exception $e) {
                return $this->redirectToRoute('app_bareme_import_form', [
                    "e" => $e->getMessage(),
                ]);
            }
            
            $this->addFlash('success', 'Bareme pour personnel temporaire importer');
            return $this->redirectToRoute('app_bareme_import_form');
        }

        return $this->renderForm('bareme/import_form.html.twig', [
            'current_page' => 'Import bareme',
            'controller_name' => 'BaremeController',
            'form' => $form,
            'form_irsa' => $form_irsa,
            'form_temp' => $form_temp,
            'user' => $request->getSession()->get('USER'),
            'firstFromLastBaremeEntry' => $rep->findByDatebareme('asc'),
            'lastFromLastBaremeEntry' => $rep->findByDatebareme('desc'),
        ]);
    }

}
