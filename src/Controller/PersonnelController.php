<?php

namespace App\Controller;

use App\Entity\PersonnelDetails;
use App\Entity\Permission;
use App\Entity\Congee;
use App\Entity\Excuse;
use App\Entity\Avertissement;
use App\Entity\IndemnitePersonnel;
use Doctrine\Persistence\ManagerRegistry;

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

use Doctrine\Common\Collections\Criteria;

class PersonnelController extends BaseController
{
    /**
     * @Route("/personnel", name="app_personnel")
     */
    public function index(Request $requestStack, Request $request,ManagerRegistry $doctrine, PersonnelDetailsRepository $rep, DirectionsRepository $drep, ContratRepository $crep): Response
    {
        $page = 1; $maxcount = 0;

        $results = [];
        $direction = ['Tous' => 'Tous'];
        $ddata = $drep->findAll();
        foreach($ddata as $item) $direction[$item->getDesignation()] = $item->getId();
        $contrat = ['Tous' => 'Tous'];
        $cdata = $crep->findAll();
        foreach($cdata as $item) $contrat[$item->getDesignation()] = $item->getId();
        
        $contrat_count = $doctrine->getRepository(PersonnelDetails::class)->createQueryBuilder('a')
        ->select('count(a.id)')
        ->where('a.duree_contrat_restant <= a.alerte_fin_contrat')
        ->getQuery()
        ->getSingleScalarResult();
        $retraite_count = $doctrine->getRepository(PersonnelDetails::class)->createQueryBuilder('a')
        ->select('count(a.id)')
        ->where('a.date_avant_retraite <= 365')
        ->getQuery()
        ->getSingleScalarResult();

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
            $page = (isset($_GET["page"])) ? $_GET["page"]: 1;
            $maxcount = $rep->countById();
            $results = $rep->findBy([], ["id" => "asc"], 10, ($page-1)*10);
        }
        return $this->renderForm('personnel/index.html.twig', [
            'current_page' => 'Personnel',
            'controller_name' => 'PersonnelController',
            'personnels' => $results,
            'form' => $form,
            'pagination' => [
                'page' => $page,
                'maxcount' => $maxcount
            ],
            'count' => [
                'retraite_count' => $retraite_count,
                'contrat_count' => $contrat_count
            ],
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    /**
     * @Route("/personnel/fiche/{id}", name="app_personnel_fiche")
     */
    public function fiche(Request $requestStack, ManagerRegistry $doctrine,int $id): Response
    {
        $results = $doctrine->getRepository(PersonnelDetails::class)->find($id);
        
        $indemnites = $doctrine->getRepository(IndemnitePersonnel::class)->findBy([
            "personnelId" => $id
        ]);
        $congee_count = $doctrine->getRepository(Congee::class)->createQueryBuilder('a')
        ->select('count(a.id)')
        ->where('a.personnel_id = '.$id)
        ->getQuery()
        ->getSingleScalarResult();
        $permission_count = $doctrine->getRepository(Permission::class)->createQueryBuilder('a')
        ->select('count(a.id)')
        ->where('a.personnel_id = '.$id)
        ->getQuery()
        ->getSingleScalarResult();
        $excuse_count = $doctrine->getRepository(Excuse::class)->createQueryBuilder('a')
        ->select('count(a.id)')
        ->where('a.personnel_id = '.$id)
        ->getQuery()
        ->getSingleScalarResult();
        $avertissement_count = $doctrine->getRepository(Avertissement::class)->createQueryBuilder('a')
        ->select('count(a.id)')
        ->where('a.personnel_id = '.$id)
        ->getQuery()
        ->getSingleScalarResult();

        return $this->render('personnel/fiche_personnel.html.twig', [
            'current_page' => 'Personnel',
            'fiche' => $results,
            'count' => [
                'congee' => $congee_count,
                'permission' => $permission_count,
                'excuse' => $excuse_count,
                'avertissement' => $avertissement_count
            ],
            'indemnites' => $indemnites,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    /**
     * @Route("/personnel/listeretraite", name="app_personnel_listeretraite")
     */
    public function liste_retraite(Request $requestStack, Request $request, PersonnelDetailsRepository $rep, DirectionsRepository $drep, ContratRepository $crep): Response
    {
        $results = [];

        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->lte('date_avant_retraite', 365));


        $results = $rep->matching($criteria);

        return $this->renderForm('personnel/liste_retraite.html.twig', [
            'current_page' => 'Personnel',
            'controller_name' => 'PersonnelController',
            'personnels' => $results,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
/**
     * @Route("/personnel/listecontratexpiree", name="app_personnel_listecontratexpiree")
     */
    public function liste_contrat_expiree(Request $requestStack, Request $request, PersonnelDetailsRepository $rep, DirectionsRepository $drep, ContratRepository $crep): Response
    {
        $results = [];

        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->lte('duree_contrat_restant', 'alerte_fin_contrat'));


        $results = $rep->matching($criteria);

        return $this->renderForm('personnel/liste_contratexpiree.html.twig', [
            'current_page' => 'Personnel',
            'controller_name' => 'PersonnelController',
            'personnels' => $results,
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
