<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Actor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=1; $i <= 10; $i++) { 
            $actor = new Actor();
            $actor->setName($faker->name($gender = 'male'|'female'));
            for ($j=1; $j <=3 ; $j++) { 
                $actor->addProgram($this->getReference('program_' . $faker->numberBetween(1, 20)));
            }
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ActorFixtures dépend
        return [
          ProgramFixtures::class,
        ];
    }
}
