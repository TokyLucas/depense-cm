<?php

namespace App\Controller\CRUD;

use App\Entity\Indemnite;
use App\Entity\IndemnitePersonnel;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

use App\Form\IndemniteCRUDType;

class IndemniteCRUDController extends AbstractController
{
    /**
     * @Route("/indemnite/add/{id}", name="app_indemnite")
     */
    public function index(RequestStack $requestStack ,Request $request ,ManagerRegistry $doctrine, int $id): Response
    {
        $indemnite = [];
        $ddata = $doctrine->getRepository(Indemnite::class)->findAll();
        foreach($ddata as $item) $indemnite[$item->getDesignation()] = $item->getId();

        $form = $this->createForm(IndemniteCRUDType::class, [
            'indemnite' => $indemnite,
            'personnel_id' => $id
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $personnelId = $form->get('personnelId')->getData();
            $IndemniteId = $form->get('IndemniteId')->getData();
            $montant = $form->get('montant')->getData();
            $indemnite = new IndemnitePersonnel();
            $indemnite->setPersonnelId($personnelId);
            $indemnite->setIndemniteId($IndemniteId);
            $indemnite->setMontant($montant);
            $doctrine->getRepository(IndemnitePersonnel::class)->add($indemnite, true);
            return $this->redirect("/personnel/fiche".$id);
        }

        return $this->renderForm('crud/indemnite_crud/index.html.twig', [
            'controller_name' => 'IndemniteCRUDController',
            'current_page' => 'Indemnite',
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/indemnite/remove/{id}/{idpersonnel}", name="app_indemnite_remove")
     */
    public function remove(ManagerRegistry $doctrine, int $id, int $idpersonnel): Response
    {
        $entity = $doctrine->getRepository(IndemnitePersonnel::class)->find($id);
        $doctrine->getRepository(IndemnitePersonnel::class)->remove($entity, true);
        return $this->redirect('/personnel/fiche/'.$idpersonnel);
    }
}
