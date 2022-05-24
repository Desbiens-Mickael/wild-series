<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        [
            'title' => 'Rien que pour vos cheveux', 'synopsis' => "Agent d'élite des services secrets israéliens, Zohan a un rêve secret : devenir coiffeur en Amérique",
            'poster'=> 'Rien_que_pour_vos_cheveux.jpg', 'category' => 'category_Humour',
        ],
        [
            'title' => 'Dragon ball super', 'synopsis' => "Dragon Ball Super se passe après la défaite de Majin Buu",
            'poster'=> 'Dragon_ball_super.jpg', 'category' => 'category_Animation',
        ],
        [
            'title' => 'Dark', 'synopsis' => "En 2019, le policier Ulrich Nielsen cherche désespérément son fils disparu, Mikkel, âgé de 12 ans",
            'poster'=> 'Dark.jpg', 'category' => 'category_Horreur',
        ],
        [
            'title' => 'Stranger_things', 'synopsis' => "Lorsque Will Byers disparaît de son domicile, ses amis se lancent dans une recherche semée d'embûches pour le retrouver",
            'poster'=> 'Stranger_things.jpg', 'category' => 'category_Fantastique',
        ],
        [
            'title' => 'Un_homme_en_colère', 'synopsis' => "Un convoyeur de fond fraîchement engagé surprend ses collègues par l'incroyable précision de ses tirs de riposte alors qu'ils subissent les assauts de braqueurs expérimentés",
            'poster'=> 'Un_homme_en_colère.jpg', 'category' => 'category_Action',
        ],
        [
            'title' => 'Cursed', 'synopsis' => "Une relecture de la légende du Roi Arthur vue à travers les yeux de Nimue, une adolescente dotée d'un mystérieux don, destinée à devenir la Dame du Lac",
            'poster'=> 'Cursed.jpg', 'category' => 'category_Aventure',
        ],
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $element) {
            $program = new Program();
            $program->setTitle($element['title']);
            $program->setSynopsis($element['synopsis']);
            $program->setPoster($element['poster']);
            $program->setCategory($this->getReference($element['category']));
            $manager->persist($program);
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
