<?php

namespace App\Controller\CRUD;

use App\Repository\UserRepository;
use App\Repository\DirectionsRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Directions;
use App\Form\DirectionsCRUDType;

class DirectionCRUDController extends AbstractController
{
    /**
     * @Route("/directions", name="app_direction")
     */
    public function index(RequestStack $requestStack, DirectionsRepository $drrep): Response
    {
        $results = $drrep->findAll();

        return $this->render('crud/direction_crud/index.html.twig', [
            'controller_name' => 'DirectionCRUDController',
            'current_page' => 'Direction',
            'directions' => $results,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/directions/add", name="app_direction_add")
     */
    public function add(RequestStack $requestStack,Request $request, DirectionsRepository $drrep): Response
    {
        $form = $this->createForm(DirectionsCRUDType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = new Directions();
            $entity->setDesignation($form->get("designation")->getData());
            $drrep->add($entity, true);

            return $this->redirectToRoute('app_direction');
        }

        return $this->renderForm('crud/direction_crud/directions_add.html.twig', [
            'controller_name' => 'DirectionCRUDController',
            'current_page' => 'Direction',
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    
    /**
     * @Route("/directions/remove/{id}", name="app_direction_remove")
     */
    public function remove(DirectionsRepository $drrep, int $id): Response
    {
        $entity = $drrep->find($id);
        $drrep->remove($entity, true);
        
        return $this->redirectToRoute('app_direction');
    }
}
