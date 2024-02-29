<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class Client
{
    const string ENDPOINT = "https://candidate-testing.api.royal-apps.io/api/v2/";
    private HttpClientInterface $client;
    public function __construct(
        private ?string $token = null,
    ) {
        $this->client = HttpClient::create();
    }

    private function _request($method, $endpoint, $body = null)
    {
        try{
            $options['headers'] = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization:' . $this->token
            ];

            if($body) {
                $options['body'] = json_encode($body);
            }

            $response = $this->client->request($method, self::ENDPOINT . $endpoint, $options);
            $statusCode = $response->getStatusCode();

            if ($statusCode >= 200 && $statusCode < 300) {
                return json_decode($response->getContent());
            }

            return null;
        } catch (
            ClientExceptionInterface|
            TransportExceptionInterface|
            ServerExceptionInterface|
            RedirectionExceptionInterface
        ) {
            return null;
        }
    }

    public function login(User $user): void
    {
            $body = [
                'email' => $user->getEmail(),
                'password' => $user->getPassword()
            ];

            $content = $this->_request('POST', 'token', $body);
            $this->token = $content?->token_key;
    }

    public function hasToken(): bool
    {
        return !!$this->token;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function author_list($orderBy = 'id', $order = 'ASC', $limit = 12, $page = 1)
    {
        return $this->_request(
            'GET',
            "authors?orderBy{$orderBy}=&direction={$order}&limit={$limit}&page={$page}"
        );
    }

    public function author(int $authorId)
    {
        return $this->_request('GET', "authors/$authorId");
    }

    public function author_delete(int $authorId): bool
    {
        return $this->_request('DELETE', "authors/$authorId") !== null;
    }


    public function author_add(Author $author): bool
    {
        $body = [
            'first_name' => $author->getFirstName(),
            'last_name' => $author->getLastName(),
            'birthday' => $author->getBirthday()->format('c'),
            'biography' => $author->getBiography(),
            'gender' => $author->getGender(),
            'place_of_birth' => $author->getPlaceOfBirth(),
        ];
        return $this->_request('POST', 'authors', $body) !== null;
    }

    public function book(int $bookId)
    {
        return $this->_request('GET', "books/$bookId");
    }

    public function book_delete(int $bookId): bool
    {
        return $this->_request('DELETE', "books/$bookId") !== null;
    }

    public function book_add(Book $book): bool
    {
            $body = [
                'author' => $book->getAuthor(),
                'title' => $book->getTitle(),
                'release_date' => $book->getReleaseDate()->format('c'),
                'description' => $book->getDescription(),
                'isbn' => $book->getIsbn(),
                'format' => $book->getFormat(),
                'number_of_pages' => $book->getNumberOfPages(),
            ];
            return $this->_request('POST', 'books', $body) !== null;
    }

    public function me()
    {
        return $this->_request('GET', 'me');
    }
}
