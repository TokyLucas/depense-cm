<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class BaseController extends AbstractController
{
    public $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    // /**
    //  * @Route("/base", name="app_base")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('base/index.html.twig', [
    //         'controller_name' => 'BaseController',
    //     ]);
    // }
}
