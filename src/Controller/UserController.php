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
use App\Form\UsersEditPasswordType;

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
     * @Route("/profile/{id}", name="app_profile")
     */
    public function profile(RequestStack $requestStack, Request $request, UserRepository $rep, UserRolesRepository $urrep, int $id): Response
    {
        $profile = $rep->find($id);
        $roles = $urrep->findBy([
            "user_id" => $profile->getId()
        ]);
        $form = $this->createForm(UsersEditPasswordType::class, null);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $ancien = sha1($form->get("ancienmotdepasse")->getData());
            $nouveau = sha1($form->get("nouveaumotdepasse")->getData());
            $confirmation = sha1($form->get("confirmermotdepasse")->getData());
            if($ancien != $profile->getMotdepasse()){
                $this->addFlash('danger', 'Ancien mot de passe incorrecte');
            }
            else if($nouveau != $confirmation){
                $this->addFlash('danger', 'Confirmation de mot de passe incorrecte');
            }
            else if(($ancien == $profile->getMotdepasse()) && ($nouveau == $confirmation)){
                $profile->setMotdepasse($nouveau);
                $rep->add($profile, true);
                $this->addFlash('success', 'Mot de passe mis a jour');
            }
        }
        $profile->setRoles($roles);
        return $this->render('user/profile.html.twig', [
            'current_page' => 'Utilisateurs',
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'profile' => $profile,
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
            
            // if($data == null) return $this->redirect('/connexion?error=creds');
            if($data == null){
                return $this->redirectToRoute('se_connecter', [
                    "error" => "creds"
                ]);
            }

            $roles = $urrep->findBy([
                "user_id" => $data->getId()
            ]);
            $data->setRoles($roles);

            $session = $requestStack->getSession();
            $session->set('USER', $data);

            // return $this->redirect('/personnel');
            return $this->redirectToRoute('app_personnel');
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
