<?php

namespace App\Controller\CRUD;

use App\Repository\UserRepository;
use App\Repository\RolesRepository;
use App\Repository\UserRoleRepository;
use App\Repository\UserRolesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\UsersCRUDType;
use App\Entity\User;
use App\Entity\UserRole;
use App\Entity\UserRoles;

class UserCRUDController extends AbstractController
{
    /**
     * @Route("/user/add", name="user_add")
     */
    public function add(RequestStack $requestStack, Request $request, UserRepository $rep): Response
    {
        $form = $this->createForm(UsersCRUDType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $identifiant = $form->get('identifiant')->getData();
            $motdepasse = $form->get('motdepasse')->getData();

            $user = new User();
            $user->setIdentifiant($identifiant);
            $user->setMotdepasse(sha1($motdepasse));
            $rep->add($user, true);

            return $this->redirect('/user');
        }

        return $this->renderForm('crud/user_crud/user_add.html.twig', [
            'current_page' => 'Utilisateurs',
            'form' => $form,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }

    /**
     * @Route("/user/addrole/{id}", name="user_add_role_form")
     */
    public function addroleform(RequestStack $requestStack, Request $request, UserRepository $rep, RolesRepository $urrep, int $id): Response
    {
        $roles = $urrep->findAll();
        $user = $rep->find($id);

        return $this->renderForm('crud/user_crud/user_add_role.html.twig', [
            'current_page' => 'Utilisateurs',
            'id' => $id,
            'selecteduser' => $user,
            'roles' => $roles,
            'user' => $requestStack->getSession()->get('USER')
        ]);
    }
    /**
     * @Route("/user/addrole", name="user_add_role")
     */
    public function addrole(RequestStack $requestStack, Request $request, UserRoleRepository $urrep): Response
    {
        $user_role = new UserRole();
        $user_role->setUserId($request->request->get('user_id'));
        $user_role->setRoleId($request->request->get('role_id'));
        $roles = $urrep->add($user_role, true);

        return $this->redirect("/user");
    }
}
