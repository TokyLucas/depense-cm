<?php

namespace App\Controller\CRUD;

use App\Repository\UserRepository;
use App\Repository\ContratRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Contrat;
use App\Form\ContratsCRUDType;

class ContratCRUDController extends AbstractController
{
        /**
     * @Route("/contrat", name="app_contrat")
     */
    public function index(RequestStack $requestStack, ContratRepository $crep): Response
    {
        $results = $crep->findAll();

        return $this->render('crud/contrat_crud/index.html.twig', [
            'controller_name' => 'ContratCRUDController',
            'current_page' => 'Contrat',
            'contrats' => $results,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/contrat/add", name="app_contrat_add")
     */
    public function add(RequestStack $requestStack,Request $request, ContratRepository $crep): Response
    {
        $form = $this->createForm(ContratsCRUDType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entity = new Contrat();
            $entity->setDesignation($form->get("designation")->getData());
            $crep->add($entity, true);

            return $this->redirect('/contrat');
        }

        return $this->renderForm('crud/contrat_crud/contrat_add.html.twig', [
            'controller_name' => 'ContratCRUDController',
            'current_page' => 'Contrat',
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    
    /**
     * @Route("/contrat/remove/{id}", name="app_contrat_remove")
     */
    public function remove(ContratRepository $crep, int $id): Response
    {
        $entity = $crep->find($id);
        $crep->remove($entity, true);
        return $this->redirect('/contrat');
    }
}
