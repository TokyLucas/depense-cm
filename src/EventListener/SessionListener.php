<?php

namespace App\EventListener;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\BrowserKit\Request;

class SessionListener
{

    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();
        $session = $request->getSession(); 
        $user = $session->get("USER");
        
        if($pathInfo != '/connexion'){
            $controller = $request->get("_controller");

            if ($user == null) {
                // dd($request);
                $event->setResponse(
                    new RedirectResponse(
                        $this->router->generate(
                            'connexion',
                            [
                                'error' => 'session'
                            ]
                        )
                    )
                );
            } 
            else {
                $clearance = "admin";
                if(str_starts_with($controller, "App\Controller\CRUD")){
                    $clearance = "admin/rh";
                    foreach($user->getRoles() as $role){
                        if($role->getDesignation() == "admin" || $role->getDesignation() == "rh") break;
                        else{
                            $event->setResponse(
                                new RedirectResponse(
                                    $this->router->generate(
                                        'connexion',
                                        [
                                            'error' => 'clearance',
                                            'clearance' => $clearance
                                        ]
                                    )
                                )
                            );
                        }
                    }
                }else if(str_starts_with($controller, "App\Controller\SalaireController")){
                    $clearance = "admin/depense";
                    foreach($user->getRoles() as $role){
                        if($role->getDesignation() == "admin" || $role->getDesignation() == "depense") break;
                        else{
                            $event->setResponse(
                                new RedirectResponse(
                                    $this->router->generate(
                                        'connexion',
                                        [
                                            'error' => 'clearance',
                                            'clearance' => $clearance
                                        ]
                                    )
                                )
                            );
                        }
                    }
                }
            }
        }
        
    }
}