<?php

namespace App\Service;

use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;

class PublisherService
{
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update(Publisher $publisher, $data)
    {
        $publisher->setName($data['name']);
        $publisher->setAddress($data['address']);

        $this->entityManager->persist($publisher);

        $this->entityManager->flush();
    }

    public function delete(Publisher $publisher)
    {
        $publisherBooks = $publisher->getBooks();

        foreach ($publisherBooks as $publisherBook) {
            $publisher->removeBook($publisherBook);
        }

        $this->entityManager->remove($publisher);
        $this->entityManager->flush();
    }
}
