<?php

namespace App\Controller;

use App\Repository\PersonnelDetailsRepository;
use App\Repository\DirectionsRepository;
use App\Repository\ContratRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\BaseController;
use App\Form\PersonnelSearchType;

class PersonnelController extends BaseController
{
    /**
     * @Route("/personnel", name="app_personnel")
     */
    public function index(Request $requestStack, Request $request, PersonnelDetailsRepository $rep, DirectionsRepository $drep, ContratRepository $crep): Response
    {
        $results = [];
        $direction = ['Tous' => 'Tous'];
        $ddata = $drep->findAll();
        foreach($ddata as $item) $direction[$item->getDesignation()] = $item->getId();
        $contrat = ['Tous' => 'Tous'];
        $cdata = $crep->findAll();
        foreach($cdata as $item) $contrat[$item->getDesignation()] = $item->getId();

        $form = $this->createForm(PersonnelSearchType::class, [
            'direction' => $direction,
            'contrat' => $contrat,
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            $dir = $form->get('direction')->getData();
            $contrat = $form->get('contrat')->getData();
            $indice = $form->get('indice')->getData();
            $results = $rep->search($nom, $prenom, $dir, $contrat, $indice);
        }else{
            $results = $rep->findAll();
        }

        return $this->renderForm('personnel/index.html.twig', [
            'current_page' => 'Personnel',
            'controller_name' => 'PersonnelController',
            'personnels' => $results,
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    /**
     * @Route("/personnel/{id}", name="app_personnel_fiche")
     */
    public function fiche(Request $requestStack, PersonnelDetailsRepository $rep, int $id): Response
    {
        $results = $rep->find($id);
        return $this->render('personnel/fiche_personnel.html.twig', [
            'current_page' => 'Personnel',
            'fiche' => $results,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    /**
     * @Route("/personnel/addposte/{id}", name="app_personnel_addposte")
     */
    public function addposte(Request $requestStack, PersonnelDetailsRepository $rep, int $id): Response
    {
        $results = $rep->find($id);
        return $this->render('personnel/fiche_personnel.html.twig', [
            'current_page' => 'Personnel',
            'personnel' => $results,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
}
