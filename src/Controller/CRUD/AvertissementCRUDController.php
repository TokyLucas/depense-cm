<?php

namespace App\Controller\CRUD;

use App\Entity\Avertissement;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AvertissementCRUDType;

class AvertissementCRUDController extends AbstractController
{
    /**
     * @Route("/avertissements/{id}", name="app_avertissements")
     */
    public function index(RequestStack $requestStack, Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $results = $doctrine->getRepository(Avertissement::class)->findBy([
            'personnel_id' => $id
        ],
        [
            "id" => "desc"
        ]);
        $form = $this->createForm(AvertissementCRUDType::class, [
            'personnel_id' => $id
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $avertissement = new Avertissement();
            $avertissement->setMotif($form->get('motif')->getData());
            $avertissement->setDate($form->get('date')->getData());
            $avertissement->setPersonnelId($form->get('personnel_id')->getData());
            $doctrine->getRepository(Avertissement::class)->add($avertissement, true);
            // return $this->redirect('/avertissements/'.$id);
            return $this->redirectToRoute('app_avertissements', [
                "id" => $id
            ]);
        }
        return $this->renderForm('crud/avertissement_crud/index.html.twig', [
            'controller_name' => 'AvertissementCRUDController',
            'current_page' => 'Avertissement',
            'excuses' => $results,
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
}
