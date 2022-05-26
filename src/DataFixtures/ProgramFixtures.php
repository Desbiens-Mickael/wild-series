<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        [
            'title' => 'Black Clover', 'poster'=> 'Black-Clover.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'Carnival row', 'poster'=> 'Carnival_row.jpg', 'category' => 'category_Fantastique', 'country' => 'America',
        ],
        [
            'title' => 'Queen south', 'poster'=> 'Queen_south.jpg', 'category' => 'category_Action', 'country' => 'America',
        ],
        [
            'title' => 'The Witcher', 'poster'=> 'The_Witcher.jpg', 'category' => 'category_Aventure', 'country' => 'America',
        ],
        [
            'title' => 'Dark', 'poster'=> 'Dark.jpg', 'category' => 'category_Horreur', 'country' => 'America',
        ],
        [
            'title' => 'Glee', 'poster'=> 'Glee.jpg', 'category' => 'category_Humour', 'country' => 'America',
        ],
        [
            'title' => 'Cursed', 'poster'=> 'Cursed.jpg', 'category' => 'category_Aventure', 'country' => 'America',
        ],
        [
            'title' => 'Dragon ball super', 'poster'=> 'Dragon_ball_super.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'Grimm', 'poster'=> 'Grimm.jpg', 'category' => 'category_Fantastique', 'country' => 'America',
        ],
        [
            'title' => 'Haikyu!!', 'poster'=> 'Haikyu.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'House of cards', 'poster'=> 'House_of_cards.jpg', 'category' => 'category_Action', 'country' => 'America',
        ],
        [
            'title' => 'Jujutsu kaisen', 'poster'=> 'Jujutsu_kaisen.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'Locke & key', 'poster'=> 'Locke_&_key.jpg', 'category' => 'category_Fantastique', 'country' => 'America',
        ],
        [
            'title' => 'Mushoku Tensei Jobles Reincarnation', 'poster'=> 'Mushoku_Tensei_Jobles__Reincarnation.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'My hero academia', 'poster'=> 'My_hero_academia.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'One peace', 'poster'=> 'One_peace.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'Squid game', 'poster'=> 'Squid_game.jpg', 'category' => 'category_Action', 'country' => 'Korea',
        ],
        [
            'title' => 'Stranger things', 'poster'=> 'Stranger_things.jpg', 'category' => 'category_Fantastique', 'country' => 'America',
        ],
        [
            'title' => 'Supernatural', 'poster'=> 'Supernatural.jpg', 'category' => 'category_Horreur', 'country' => 'America',
        ],
        [
            'title' => 'Terra nova', 'poster'=> 'Terra_nova.jpg', 'category' => 'category_Aventure', 'country' => 'America',
        ],
        [
            'title' => 'The bigbang theory', 'poster'=> 'The_bigbang_theory.jpg', 'category' => 'category_Humour', 'country' => 'America',
        ],
    ];
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        foreach (self::PROGRAMS as $key => $element) {
            $program = new Program();
            $program->setTitle($element['title']);
            $program->setSynopsis($faker->text(200));
            $program->setPoster($element['poster']);
            $program->setCountry($element['country']);
            $program->setYear($faker->year());
            $program->setCategory($this->getReference($element['category']));
            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
            
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }


}
