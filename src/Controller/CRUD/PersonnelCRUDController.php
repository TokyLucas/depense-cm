<?php

namespace App\Controller\CRUD;

use App\Repository\PersonnelRepository;
use App\Entity\Personnel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\PersonnelCRUDType;


class PersonnelCRUDController extends AbstractController
{
    /**
     * @Route("/personnel/add", name="app_personnel_add")
     */
    public function add(RequestStack $requestStack, Request $request, PersonnelRepository $rep): Response
    {
        $form = $this->createForm(PersonnelCRUDType::class, null);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            try{
                $nom = $form->get('nom')->getData();
                $prenom = $form->get('prenom')->getData();
                $cin = $form->get('CIN')->getData();
                $matricule = $form->get('Matricule')->getData();
                $datedenaissance = $form->get('datedenaissance')->getData();
                $datedenaissance = \DateTime::createFromFormat("Y-m-d",date_format($datedenaissance,"Y-m-d"));
                $nbenfant = $form->get('nbenfant')->getData();
                
                $personnel = new Personnel();
                $personnel->setNom($nom);
                $personnel->setPrenom($prenom);
                $personnel->setCIN($cin);
                $personnel->setMatricule($matricule);
                $personnel->setDatedenaissance($datedenaissance);
                $personnel->setNbEnfant($nbenfant);
            } catch (Exception $ex) {
                dd($ex);
            }

            $rep->add($personnel, true);

            return $this->redirect('/personnel');
        }

        return $this->renderForm('crud/personnel_crud/personnel_add.html.twig', [
            'current_page' => 'Personnel',
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
}
