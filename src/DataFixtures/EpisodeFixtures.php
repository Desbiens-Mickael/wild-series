<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        
        for ($i = 0; $i < count(ProgramFixtures::PROGRAMS); $i++) { 
            for ($j = 1; $j <= 5; $j++) {
                for ($e = 1; $e <= 8; $e++) { 
                $episode = new Episode();
                $episode->setTitle($faker->sentence(3));
                $episode->setNumber($e);
                $episode->setSynopsis($faker->text(200));
                $episode->setSeason($this->getReference('season_' . $i . $j));
                $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          SeasonFixtures::class,
        ];
    }
}
