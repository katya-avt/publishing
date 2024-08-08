<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $publishers = $manager->getRepository(Publisher::class)->findAll();

        for ($i = 1; $i <= 10; $i++) {
            $book = new Book();
            $book->setName('Book ' . $i);
            $book->setPublicationYear(mt_rand(2020, 2024));

            $publisher = $publishers[array_rand($publishers)];
            $book->setPublisher($publisher);

            $manager->persist($book);
        }

        $manager->flush();

        $books = $manager->getRepository(Book::class)->findAll();
        $authors = $manager->getRepository(Author::class)->findAll();

        foreach ($books as $book) {
            shuffle($authors);

            for ($i = 0; $i <= 1; $i++) {
                $book->addAuthor($authors[$i]);
                $manager->persist($book);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PublisherFixtures::class,
            AuthorFixtures::class,
        ];
    }
}
