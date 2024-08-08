<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function store($data)
    {
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($data['publisher_id']);
        $author = $this->entityManager->getRepository(Author::class)->find($data['author_id']);

        $book = new Book();
        $book->setName($data['name']);
        $book->setPublicationYear($data['publication_year']);
        $book->setPublisher($publisher);
        $book->addAuthor($author);

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function delete(Book $book)
    {
        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }
}
