<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [
        ['title' => '1', 'number' => 1, 'synopsis' => "Il était une fois", 'season' => 'season_1'],
        ['title' => '2', 'number' => 22, 'synopsis' => "Il était une fois", 'season' => 'season_2'],
        ['title' => '3', 'number' => 78, 'synopsis' => "Il était une fois", 'season' => 'season_3'],
        ['title' => '4', 'number' => 55, 'synopsis' => "Il était une fois", 'season' => 'season_4'],
        ['title' => '5', 'number' => 5, 'synopsis' => "Il était une fois", 'season' => 'season_5'],
        ['title' => '6', 'number' => 2, 'synopsis' => "Il était une fois", 'season' => 'season_0'],
        ['title' => '7', 'number' => 3, 'synopsis' => "Il était une fois", 'season' => 'season_0'],
        ['title' => '8', 'number' => 33, 'synopsis' => "Il était une fois", 'season' => 'season_5'],
        ['title' => '9', 'number' => 46, 'synopsis' => "Il était une fois", 'season' => 'season_4'],
        ['title' => '10', 'number' => 58, 'synopsis' => "Il était une fois", 'season' => 'season_3'],
        ['title' => '11', 'number' => 68, 'synopsis' => "Il était une fois", 'season' => 'season_2'],
        ['title' => '12', 'number' => 77, 'synopsis' => "Il était une fois", 'season' => 'season_1'],
        ['title' => '13', 'number' => 17, 'synopsis' => "Il était une fois", 'season' => 'season_5'],
        ['title' => '14', 'number' => 2, 'synopsis' => "Il était une fois", 'season' => 'season_0'],
        ['title' => '15', 'number' => 55, 'synopsis' => "Il était une fois", 'season' => 'season_4'],
        ['title' => '16', 'number' => 18, 'synopsis' => "Il était une fois", 'season' => 'season_4'],

    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $element) {
            $episode = new Episode();
            $episode->setTitle($element['title']);
            $episode->setNumber($element['number']);
            $episode->setSynopsis($element['synopsis']);
            $episode->setSeason($this->getReference($element['season']));
            $manager->persist($episode);
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
