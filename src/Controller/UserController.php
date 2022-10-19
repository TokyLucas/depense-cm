<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\UserRolesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\UserFormType;

class UserController extends AbstractController
{

    /**
     * @Route("/user", name="app_user")
     */
    public function index(RequestStack $requestStack, UserRepository $rep, UserRolesRepository $urrep): Response
    {
        $results = $rep->findAll();

        foreach ($results as $user){
            $roles = $urrep->findBy([
                "user_id" => $user->getId()
            ]);
            $user->setRoles($roles);
        }

        return $this->render('user/index.html.twig', [
            'current_page' => 'Utilisateurs',
            'controller_name' => 'UserController',
            'personnels' => $results,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    /**
     * @Route("/connexion", name="se_connecter")
     */
    public function seConnecterForm(RequestStack $requestStack, Request $request, UserRepository $rep, UserRolesRepository $urrep): Response
    {
        $form = $this->createForm(UserFormType::class, null);
        $form->handleRequest($request);
        $error = (isset($_GET['error'])) ? $_GET['error'] : null;

        if ($form->isSubmitted()) {
            $identifiant = $form->get('identifiant')->getData();
            $motdepasse = $form->get('motdepasse')->getData();
            $data = $rep->findOneBy([
                'identifiant' => $identifiant,
                'motdepasse' => sha1($motdepasse)
            ]);
            if($data == null) return $this->redirect('/connexion?error=creds');

            $roles = $urrep->findBy([
                "user_id" => $data->getId()
            ]);
            $data->setRoles($roles);

            $session = $requestStack->getSession();
            $session->set('USER', $data);

            return $this->redirect('/personnel');
        }

        return $this->renderForm('user/connexion.html.twig', [
            'current_page' => 'Se connecter',
            'form' => $form,
            'erreur' => $error
        ]);
    }
    
    /**
     * @Route("/deconnexion", name="se_de_connecter")
     */
    public function seDeconnecter(Request $requestStack): Response
    {
        $session = $requestStack->getSession();
        $session->remove('USER');

        return $this->redirectToRoute('connexion');
    }


}
