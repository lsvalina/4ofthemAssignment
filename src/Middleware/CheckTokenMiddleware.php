<?php

namespace App\Middleware;

use Override;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class CheckTokenMiddleware
{

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->attributes->get('_route') === 'app_auth_login') {
            return; // Do not apply middleware for this route
        }

        if (!$this->isTokenSet($request)) {
            $response = new RedirectResponse('/login');
            $event->setResponse($response);
        }
    }

    private function isTokenSet(Request $request):bool
    {
        return $request->cookies->get('token') !== null;
    }
}
