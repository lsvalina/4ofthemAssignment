<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/login', methods: ['POST', 'GET'])]
    function login(Request $request): Response
    {
        $user = new User();
        $client = new Client($request->cookies->get('token'));
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->login($user);
        }

        if ($client->hasToken()) {
            $response = new RedirectResponse('/');
            $cookie = new Cookie('token', $client->getToken());
            $cookie = $cookie->withHttpOnly();
            $response->headers->setCookie($cookie);
            $response->send();
        }
        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', methods: ['GET'])]
    public function logout(): RedirectResponse
    {
        $response = new RedirectResponse('/login');
        $response->headers->clearCookie('token');
        $response->headers->clearCookie('me');
        return $response;
    }
}
