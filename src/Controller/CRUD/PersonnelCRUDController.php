<?php

namespace App\Controller\CRUD;

use App\Entity\Personnel;
use App\Entity\PersonnelDetails;
use App\Entity\Contrat;
use App\Entity\Service;
use App\Entity\Directions;
use App\Entity\Poste;
use App\Entity\Sexe;
use App\Entity\Situationmatrimoniale;
use App\Entity\PersonnelPoste;

use App\Entity\IndemnitePersonnel;
use App\Entity\Congee;
use App\Entity\Permission;
use App\Entity\Excuse;
use App\Entity\Avertissement;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

use App\Form\PersonnelCRUDType;
use App\Form\PersonnelEditType;
use App\Form\PosteEditType;


class PersonnelCRUDController extends AbstractController
{
    /**
     * @Route("/personnel/add", name="app_personnel_add")
     */
    public function add(RequestStack $requestStack, Request $request, ManagerRegistry $doctrine): Response
    {
        date_default_timezone_set('Africa/Nairobi');

        $direction = [];
        $ddata = $doctrine->getRepository(Directions::class)->findAll();
        foreach($ddata as $item) $direction[$item->getDesignation()] = $item->getId();
        $contrat = [];
        $cdata = $doctrine->getRepository(Contrat::class)->findAll();
        foreach($cdata as $item) $contrat[$item->getDesignation()] = $item->getId();
        $sexe = [];
        $sdata = $doctrine->getRepository(Sexe::class)->findAll();
        foreach($sdata as $item) $sexe[$item->getDesignation()] = $item->getId();
        $situationmatrimoniale = [];
        $smdata = $doctrine->getRepository(Situationmatrimoniale::class)->findAll();
        foreach($smdata as $item) $situationmatrimoniale[$item->getDesignation()] = $item->getId();
        
        $form = $this->createForm(PersonnelCRUDType::class, [
            'direction' => $direction,
            'contrat' => $contrat,
            'sexe' => $sexe,
            'situationmatrimoniale' => $situationmatrimoniale,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try{
                $nom = $form->get('nom')->getData();
                $prenom = $form->get('prenom')->getData();
                $cin = $form->get('CIN')->getData();
                $sexe = $form->get('sexe')->getData();
                $datedenaissance = $form->get('datedenaissance')->getData();
                $datedenaissance = \DateTime::createFromFormat("Y-m-d",date_format($datedenaissance,"Y-m-d"));
                $lieudenaissance = $form->get('lieudenaissance')->getData();
                $nbenfant = $form->get('nbenfant')->getData();
                $situationmatrimoniale =  $form->get('situationmatrimoniale')->getData();
                
                $personnel = new Personnel();
                $personnel->setNom($nom);
                $personnel->setPrenom($prenom);
                $personnel->setCIN($cin);
                $personnel->setSexeId($sexe);
                $personnel->setDatedenaissance($datedenaissance);
                $personnel->setLieudenaissance($lieudenaissance);
                $personnel->setNbEnfant($nbenfant);
                $personnel->setSituationmatrimonialeId($situationmatrimoniale);

                $doctrine->getRepository(Personnel::class)->add($personnel, true);
                $personnel_id = $personnel->getId();

                $direction_id = $form->get('direction')->getData();
                $contrat_id = $form->get('contrat')->getData();
                $categorie = $form->get('categorie')->getData();
                $indice = $form->get('indice')->getData();
                $grade = $form->get('grade')->getData();
                $designation = $form->get('poste')->getData();
                
                $poste = new Poste();
                $poste->setDesignation($designation);
                $poste->setDirectionId($direction_id);
                $poste->setContratId($contrat_id);
                $poste->setCategorie($categorie);
                $poste->setIndice($indice);
                $poste->setGrade($grade);

                $doctrine->getRepository(Poste::class)->add($poste, true);
                $poste_id = $poste->getId();
                
                $personnelPoste = new PersonnelPoste();
                $personnelPoste->setPersonnelId($personnel_id);
                $personnelPoste->setPosteId($poste_id);
                $personnelPoste->setDatedebut(new \DateTime());

                $datefin = $form->get('datefin')->getData();
                $nbjourdetravailtemporaire = $form->get('nbjourdetravailtemporaire')->getData();
                $heureparjour = $form->get('heureparjour')->getData();
                
                if ($datefin != null) $personnelPoste->setDatefin(\DateTime::createFromFormat("Y-m-d",date_format($datefin,"Y-m-d")));
                else $personnelPoste->setDatefin(null);
                $personnelPoste->setNbjourdetravailtemporaire($nbjourdetravailtemporaire);
                
                $personnelPoste->setHeureparjour($heureparjour);
                $doctrine->getRepository(PersonnelPoste::class)->add($personnelPoste, true);
            } catch (Exception $ex) {
                dd($ex);
            }

            return $this->redirect('/personnel');
        }

        return $this->renderForm('crud/personnel_crud/personnel_add.html.twig', [
            'current_page' => 'Personnel',
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/personnel/editinfo/{id}", name="app_personnel_editinfo")
     */
    public function edit(RequestStack $requestStack, Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        date_default_timezone_set('Africa/Nairobi');

        $personnel = $doctrine->getRepository(Personnel::class)->find($id);
        $sexe = [];
        $sdata = $doctrine->getRepository(Sexe::class)->findAll();
        foreach($sdata as $item) $sexe[$item->getDesignation()] = $item->getId();
        $situationmatrimoniale = [];
        $smdata = $doctrine->getRepository(Situationmatrimoniale::class)->findAll();
        foreach($smdata as $item) $situationmatrimoniale[$item->getDesignation()] = $item->getId();

        $form = $this->createForm(PersonnelEditType::class, $personnel, [
            'choices' => [
                'sexe' => $sexe,
                'situationmatrimoniale' => $situationmatrimoniale
            ]
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $personnel_form_data = $form->getData();
            $personnel_form_data->setSexeId($form->get('sexe_id')->getData());
            $personnel_form_data->setSituationmatrimonialeId($form->get('situationmatrimoniale_id')->getData());
            $doctrine->getRepository(Personnel::class)->add($personnel_form_data, true);
            return $this->redirect('/personnel/fiche/'.$id);
        }
        return $this->renderForm('crud/personnel_crud/personnel_edit.html.twig', [
            'current_page' => 'Personnel',
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/personnel/editposte/{id}", name="app_personnel_editposte")
     */
    public function editposte(RequestStack $requestStack, Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        date_default_timezone_set('Africa/Nairobi');

        $personnel = $doctrine->getRepository(Personnel::class)->find($id);
        $personnel_details = $doctrine->getRepository(PersonnelDetails::class)->findOneBy([
            "id" => $id
        ]);
        $poste_actuelle = $doctrine->getRepository(Poste::class)->find($personnel_details->getPosteId());
        $direction = [];
        $ddata = $doctrine->getRepository(Directions::class)->findAll();
        foreach($ddata as $item) $direction[$item->getDesignation()] = $item->getId();
        $contrat = [];
        $cdata = $doctrine->getRepository(Contrat::class)->findAll();
        foreach($cdata as $item) $contrat[$item->getDesignation()] = $item->getId();
        $service = [];
        $cdata = $doctrine->getRepository(Service::class)->findAll();
        foreach($cdata as $item) $service[$item->getDesignation()] = $item->getId();

        $form = $this->createForm(PosteEditType::class, $poste_actuelle, [
            'choices' => [
                'direction' => $direction,
                'contrat' => $contrat,
                'service' => $service
            ]
        ]);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $poste_form_data = $form->getData();
            $poste_form_data->setDirectionId($form->get('direction_id')->getData());
            $poste_form_data->setContratId($form->get('contrat_id')->getData());
            $poste_form_data->setServiceId($form->get('service_id')->getData());
            
            $doctrine->getRepository(Poste::class)->add($poste_form_data, true);
            
            $personnelPoste = new PersonnelPoste();
            $personnelPoste->setPersonnelId($personnel->getId());
            $personnelPoste->setPosteId($poste_form_data->getId());
            $personnelPoste->setDatedebut(new \DateTime());

            $datefin = $form->get('datefin')->getData();
            $nbjourdetravailtemporaire = $form->get('nbjourdetravailtemporaire')->getData();
            $heureparjour = $form->get('heureparjour')->getData();
            
            if ($datefin != null) $personnelPoste->setDatefin(\DateTime::createFromFormat("Y-m-d",date_format($datefin,"Y-m-d")));
            else $personnelPoste->setDatefin(null);
            $personnelPoste->setNbjourdetravailtemporaire($nbjourdetravailtemporaire);
            $personnelPoste->setHeureparjour($heureparjour);
            
            $doctrine->getRepository(PersonnelPoste::class)->add($personnelPoste, true);

            // dd($personnelPoste);

            return $this->redirect('/personnel/fiche/'.$id);
        }
        return $this->renderForm('crud/personnel_crud/personnel_edit_poste.html.twig', [
            'current_page' => 'Personnel',
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/personnel/remove/{id}", name="app_personnel_remove")
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $entities = $doctrine->getRepository(IndemnitePersonnel::class)->findBy([
            'personnelId' => $id
        ]);
        foreach($entities as $entity) $doctrine->getRepository(IndemnitePersonnel::class)->remove($entity, true);
        
        $entities = $doctrine->getRepository(Congee::class)->findBy([
            'personnel_id' => $id
        ]);
        foreach($entities as $entity) $doctrine->getRepository(Congee::class)->remove($entity, true);
        
        $entities = $doctrine->getRepository(Permission::class)->findBy([
            'personnel_id' => $id
        ]);
        foreach($entities as $entity) $doctrine->getRepository(Permission::class)->remove($entity, true);
        
        $entities = $doctrine->getRepository(Excuse::class)->findBy([
            'personnel_id' => $id
        ]);
        foreach($entities as $entity) $doctrine->getRepository(Excuse::class)->remove($entity, true);

        $entities = $doctrine->getRepository(Avertissement::class)->findBy([
            'personnel_id' => $id
        ]);
        foreach($entities as $entity) $doctrine->getRepository(Avertissement::class)->remove($entity, true);

        $pp_entities = $doctrine->getRepository(PersonnelPoste::class)->findBy([
            'personnel_id' => $id
        ]);
        foreach($pp_entities as $entity) $doctrine->getRepository(PersonnelPoste::class)->remove($entity, true);

        foreach($pp_entities as $pp_entity){
            $entities = $doctrine->getRepository(Poste::class)->findBy([
                'id' => $pp_entity->getPosteId(),
            ]);
            foreach($entities as $entity) $doctrine->getRepository(Poste::class)->remove($entity, true);
        }

        $entity = $doctrine->getRepository(Personnel::class)->find($id);
        $doctrine->getRepository(Personnel::class)->remove($entity, true);
        return $this->redirect('/personnel');
    }
}
