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
            if ($user == null) {
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
        }
        
    }
}