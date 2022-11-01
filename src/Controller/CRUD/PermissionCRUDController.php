<?php

namespace App\Controller\CRUD;

use App\Entity\Permission;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\PermissionCRUDType;

class PermissionCRUDController extends AbstractController
{
    /**
     * @Route("permission/{id}", name="app_permission")
     */
    public function index(RequestStack $requestStack, Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $results = $doctrine->getRepository(Permission::class)->findBy([
            'personnel_id' => $id
        ],
        [
            "id" => "desc"
        ]);
        $form = $this->createForm(PermissionCRUDType::class, [
            'personnel' => $id
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted()  && $form->isValid()){
            $congee = new Permission();
            $congee->setMotif($form->get('motif')->getData());
            $congee->setDuree($form->get('duree')->getData());
            $congee->setDatedebut($form->get('datedebut')->getData());
            $congee->setDatefin($form->get('datefin')->getData());
            $congee->setPersonnelId($form->get('personnel_id')->getData());
            $doctrine->getRepository(Permission::class)->add($congee, true);
            // return $this->redirect('/permission/'.$id);

            return $this->redirectToRoute('app_permission', [
                "id" => $id
            ]);
        }
        
        return $this->renderForm('crud/permission_crud/index.html.twig', [
            'controller_name' => 'ContratCRUDController',
            'current_page' => 'Permission',
            'permissions' => $results,
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
}
