<?php

namespace App\Controller\CRUD;

use App\Entity\Excuse;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\ExcuseCRUDType;

class ExcuseCRUDController extends AbstractController
{
    /**
     * @Route("/excuse/{id}", name="app_excuse")
     */
    public function index(RequestStack $requestStack, Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $results = $doctrine->getRepository(Excuse::class)->findBy([
            'personnel_id' => $id
        ],
        [
            "id" => "desc"
        ]);
        $form = $this->createForm(ExcuseCRUDType::class, [
            'personnel_id' => $id
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $excuse = new Excuse();
            $excuse->setMotif($form->get('motif')->getData());
            $excuse->setDate($form->get('date')->getData());
            $excuse->setPersonnelId($form->get('personnel_id')->getData());
            $doctrine->getRepository(Excuse::class)->add($excuse, true);
            return $this->redirect('/excuse/'.$id);
        }
        return $this->renderForm('crud/excuse_crud/index.html.twig', [
            'controller_name' => 'ExcuseCRUDController',
            'current_page' => 'Excuse',
            'excuses' => $results,
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
}
