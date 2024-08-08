<?php

namespace App\Service;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;

class AuthorService
{
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function store($data)
    {
        $author = new Author();
        $author->setName($data['name']);
        $author->setSurname($data['surname']);

        $this->entityManager->persist($author);

        $this->entityManager->flush();
    }

    public function delete(Author $author)
    {
        $this->entityManager->remove($author);
        $this->entityManager->flush();
    }
}
