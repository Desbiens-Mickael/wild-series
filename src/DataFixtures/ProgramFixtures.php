<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    private Slugify $slug;

    public const PROGRAMS = [
        [
            'title' => 'Black Clover', 'poster'=> 'Black-Clover.jpg', 'poster_lg' => 'Black-Clover-lg.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'Carnival row', 'poster'=> 'Carnival_row.jpg', 'poster_lg'=> 'Carnival_row-lg.jpg', 'category' => 'category_Fantastique', 'country' => 'America',
        ],
        [
            'title' => 'Queen south', 'poster'=> 'Queen_south.jpg', 'poster_lg'=> 'Queen_south-lg.jpg', 'category' => 'category_Action', 'country' => 'America',
        ],
        [
            'title' => 'The Witcher', 'poster'=> 'The_Witcher.jpg', 'poster_lg'=> 'The_Witcher-lg.jpg', 'category' => 'category_Aventure', 'country' => 'America',
        ],
        [
            'title' => 'Dark', 'poster'=> 'Dark.jpg', 'poster_lg'=> 'Dark-lg.jpg', 'category' => 'category_Horreur', 'country' => 'America',
        ],
        [
            'title' => 'Glee', 'poster'=> 'Glee.jpg', 'poster_lg'=> 'Glee-lg.jpg', 'category' => 'category_Humour', 'country' => 'America',
        ],
        [
            'title' => 'Cursed', 'poster'=> 'Cursed.jpg', 'poster_lg'=> 'Cursed-lg.jpg', 'category' => 'category_Aventure', 'country' => 'America',
        ],
        [
            'title' => 'Dragon ball super', 'poster'=> 'Dragon_ball_super.jpg', 'poster_lg'=> 'Dragon_ball_super-lg.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'Grimm', 'poster'=> 'Grimm.jpg', 'poster_lg'=> 'Grimm-lg.jpg', 'category' => 'category_Fantastique', 'country' => 'America',
        ],
        [
            'title' => 'Haikyu!!', 'poster'=> 'Haikyu.jpg', 'poster_lg'=> 'Haikyu-lg.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'House of cards', 'poster'=> 'House_of_cards.jpg', 'poster_lg'=> 'House_of_cards-lg.jpg', 'category' => 'category_Action', 'country' => 'America',
        ],
        [
            'title' => 'Jujutsu kaisen', 'poster'=> 'Jujutsu_kaisen.jpg', 'poster_lg'=> 'Jujutsu_kaisen-lg.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'Locke & key', 'poster'=> 'Locke_&_key.jpg', 'poster_lg'=> 'Locke_&_key-lg.jpg', 'category' => 'category_Fantastique', 'country' => 'America',
        ],
        [
            'title' => 'Mushoku Tensei Jobles Reincarnation', 'poster'=> 'Mushoku_Tensei_Jobles__Reincarnation.jpg', 'poster_lg'=> 'Mushoku_Tensei_Jobles__Reincarnation-lg.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'My hero academia', 'poster'=> 'My_hero_academia.jpg', 'poster_lg'=> 'My_hero_academia-lg.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'One peace', 'poster'=> 'One_peace.jpg', 'poster_lg'=> 'One_peace-lg.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
        [
            'title' => 'Squid game', 'poster'=> 'Squid_game.jpg', 'poster_lg'=> 'Squid_game-lg.jpg', 'category' => 'category_Action', 'country' => 'Korea',
        ],
        [
            'title' => 'Stranger things', 'poster'=> 'Stranger_things.jpg', 'poster_lg'=> 'Stranger_things-lg.jpg', 'category' => 'category_Fantastique', 'country' => 'America',
        ],
        [
            'title' => 'Supernatural', 'poster'=> 'Supernatural.jpg', 'poster_lg'=> 'Supernatural-lg.jpg', 'category' => 'category_Horreur', 'country' => 'America',
        ],
        [
            'title' => 'Terra nova', 'poster'=> 'Terra_nova.jpg', 'poster_lg'=> 'Terra_nova-lg.jpg', 'category' => 'category_Aventure', 'country' => 'America',
        ],
        [
            'title' => 'The bigbang theory', 'poster'=> 'The_bigbang_theory.jpg', 'poster_lg'=> 'The_bigbang_theory-lg.jpg', 'category' => 'category_Humour', 'country' => 'America',
        ],
        [
            'title' => 'Shadow and bone', 'poster'=> 'Shadow_and_bone.jpg', 'poster_lg'=> 'shadow-and-bone-lg.jpg', 'category' => 'category_Aventure', 'country' => 'America',
        ],
        [
            'title' => 'Bleach', 'poster'=> 'Bleach.jpg', 'poster_lg'=> 'Bleach_lg.jpg', 'category' => 'category_Animation', 'country' => 'Japan',
        ],
    ];

    public function __construct(Slugify $slug)
    {
        $this->slug = $slug;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        foreach (self::PROGRAMS as $key => $element) {
            $program = new Program();
            $program->setTitle($element['title']);
            $program->setSynopsis($faker->text(200));
            $program->setPoster($element['poster']);
            $program->setPosterLg($element['poster_lg']);
            $program->setCountry($element['country']);
            $program->setYear($faker->year());
            $program->setSlug($this->slug->generate($program->getTitle()));
            $program->setCategory($this->getReference($element['category']));
            $program->setOwner($this->getReference('user_' . rand(1,2)));
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
