<?php

namespace App\Controller;

use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author_list', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $client = new Client($request->cookies->get('token'));
        $author_list = $client->author_list(limit: 999);
        return $this->render('author/index.html.twig', [
            'authors' => $author_list,
        ]);
    }

    #[Route('/author/{id}', name: 'app_author', methods: ['GET'])]
    public function show(int $id, Request $request): Response
    {
        $client = new Client($request->cookies->get('token'));
        $author = $client->author($id);
        return $this->render('author/author.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/author/delete/{id}', name: 'app_author_delete', methods: ['GET'])]
    public function delete(int $id, Request $request): Response
    {
        $client = new Client($request->cookies->get('token'));
        $client->author_delete($id);
        return $this->index($request);
    }
}
