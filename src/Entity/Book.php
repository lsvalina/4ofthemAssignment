<?php

namespace App\Entity;

class Book
{
    private \stdClass $author;

    private string $title;

    private \DateTime $release_date;

    private string $description;

    private string $isbn;

    private string $format;

    private int $number_of_pages;

    public function getAuthor(): \stdClass
    {
        return $this->author;
    }

    public function setAuthor(\stdClass $author): void
    {
        $this->author = $author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getReleaseDate(): \DateTime
    {
        return $this->release_date;
    }

    public function setReleaseDate(\DateTime $release_date): void
    {
        $this->release_date = $release_date;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function getNumberOfPages(): int
    {
        return $this->number_of_pages;
    }

    public function setNumberOfPages(int $number_of_pages): void
    {
        $this->number_of_pages = $number_of_pages;
    }
}
