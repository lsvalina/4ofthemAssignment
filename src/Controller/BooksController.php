<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BooksController extends AbstractController
{

    #[Route('/book/delete/{id}', name: 'app_book_delete', methods: ['GET'])]
    public function delete(int $id, Request $request): Response
    {
        $client = new Client($request->cookies->get('token'));
        $book = $client->book($id);

        $client->book_delete($id);

        $response = new RedirectResponse("/author/{$book->author->id}");
        $response->send();
    }

    #[Route('/book', name: 'app_book_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {

        $client = new Client($request->cookies->get('token'));
        $message = "";
        $authors = $client->author_list(limit: 9999);

        $book = new Book();
        $form = $this->createForm(BookType::class, $book, [
            'authors' => $authors->items
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($client->book_add($book)) {
                $message = "Book {$book->getTitle()} added";
            } else {
                $message = "Cannot create book!";
            }

        }

        return $this->render('books/index.html.twig', [
            'form' => $form->createView(),
            'message' => $message
        ]);
    }
}
