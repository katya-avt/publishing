<?php

namespace App\DataFixtures;

use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PublisherFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $publisher = new Publisher();
            $publisher->setName('Publisher '. $i);
            $publisher->setAddress('House: House, Street: Street, City: City, Country: Country '. $i);

            $manager->persist($publisher);
        }

        $manager->flush();
    }
}
