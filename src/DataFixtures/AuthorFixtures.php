<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $author = new Author();
            $author->setName('Author Name '. $i);
            $author->setSurname('Author Surname '. $i);

            $manager->persist($author);
        }

        $manager->flush();
    }
}
