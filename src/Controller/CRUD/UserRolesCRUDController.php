<?php

namespace App\Controller\CRUD;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserRolesCRUDController extends AbstractController
{
    /**
     * @Route("/c/r/u/d/user/roles/c/r/u/d", name="app_c_r_u_d_user_roles_c_r_u_d")
     */
    public function index(): Response
    {
        return $this->render('crud/user_roles_crud/index.html.twig', [
            'controller_name' => 'UserRolesCRUDController',
        ]);
    }
}
