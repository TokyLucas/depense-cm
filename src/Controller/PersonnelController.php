<?php

namespace App\Controller;

use App\Repository\PersonnelRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonnelController extends AbstractController
{
    /**
     * @Route("/personnel", name="app_personnel")
     */
    public function index(PersonnelRepository $rep): Response
    {
        $results = $rep->findAll();
        return $this->render('personnel/index.html.twig', [
            'current_page' => 'Personnel',
            'controller_name' => 'PersonnelController',
            'personnels' => $results
        ]);
    }
    /**
     * @Route("/personnel/{id}", name="app_personnel_fiche")
     */
    public function fiche(PersonnelRepository $rep, int $id): Response
    {
        $results = $rep->find($id);
        return $this->render('personnel/fiche_personnel.html.twig', [
            'current_page' => 'Personnel',
            'fiche' => $results
        ]);
    }
}
