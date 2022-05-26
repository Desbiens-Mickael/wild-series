<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS = [
        ['number' => 1, 'year' => 2008, 'description' => 'Il était une fois', 'program' => 'program_0'],
        ['number' => 8, 'year' => 2008, 'description' => 'Il était une fois', 'program' => 'program_1'],
        ['number' => 5, 'year' => 2008, 'description' => 'Il était une fois', 'program' => 'program_2'],
        ['number' => 2, 'year' => 2008, 'description' => 'Il était une fois', 'program' => 'program_3'],
        ['number' => 4, 'year' => 2008, 'description' => 'Il était une fois', 'program' => 'program_4'],
        ['number' => 3, 'year' => 2008, 'description' => 'Il était une fois', 'program' => 'program_5'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as  $key => $element) {
            $season = new Season();
            $season->setNumber($element['number']);
            $season->setYear($element['year']);
            $season->setDescription($element['description']);
            $season->setProgram($this->getReference($element['program']));
            $manager->persist($season);
            $this->addReference('season_' . $key, $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ProgramFixtures::class,
        ];
    }
}
