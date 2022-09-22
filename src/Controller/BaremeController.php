<?php

namespace App\Controller;

use App\Repository\BaremeRepository;
use App\Entity\Bareme;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Form\BaremeFormType;

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
    public function import_form(Request $request, BaremeRepository $rep): Response
    {
        set_time_limit(120);
        $form = $this->createForm(BaremeFormType::class, null);
        $form->handleRequest($request);
        $fileFolder = __DIR__ . '/../../public/uploads/';

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
                return $this->redirect('/bareme/import?e='.$e->getMessage());
            }

            return $this->redirect('/bareme/import');
        }

        return $this->renderForm('bareme/import_form.html.twig', [
            'current_page' => 'Import bareme',
            'controller_name' => 'BaremeController',
            'form' => $form,
            'user' => $request->getSession()->get('USER'),
            'firstFromLastBaremeEntry' => $rep->findByDatebareme('asc'),
            'lastFromLastBaremeEntry' => $rep->findByDatebareme('desc'),
        ]);
    }

    
}
