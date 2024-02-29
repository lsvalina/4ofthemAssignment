<?php

namespace App\Controller;

use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $client = new Client($request->cookies->get('token'));
        $me = $client->me();
        $response = $this->render('home/index.html.twig');
        $response->headers->setCookie(new Cookie('me', "{$me->first_name} {$me->last_name}"));
        return $response;
    }
}
