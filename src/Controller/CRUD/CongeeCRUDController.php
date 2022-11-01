<?php

namespace App\Controller\CRUD;

use App\Entity\Congee;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\CongeeCRUDType;

class CongeeCRUDController extends AbstractController
{
    /**
     * @Route("/congee/{id}", name="app_congee")
     */
    public function index(RequestStack $requestStack, Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $results = $doctrine->getRepository(Congee::class)->findBy([
            'personnel_id' => $id
        ],
        [
            "id" => "desc"
        ]);
        $form = $this->createForm(CongeeCRUDType::class, [
            'personnel' => $id
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $congee = new Congee();
            $congee->setMotif($form->get('motif')->getData());
            $congee->setDuree($form->get('duree')->getData());
            $congee->setDatedebut($form->get('datedebut')->getData());
            $congee->setDatefin($form->get('datefin')->getData());
            $congee->setPersonnelId($form->get('personnel_id')->getData());
            $doctrine->getRepository(Congee::class)->add($congee, true);
            // return $this->redirect('/congee/'.$id);

            return $this->redirectToRoute('app_congee', [
                "id" => $id
            ]);
        }

        return $this->renderForm('crud/congee_crud/index.html.twig', [
            'controller_name' => 'ContratCRUDController',
            'current_page' => 'Congee',
            'congees' => $results,
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
}
