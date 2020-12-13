<?php

namespace App\DataFixtures;

use App\Entity\Actor;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Barbie',
        'Brandon Miaou',
        'Hackerman',
        'Ta soeur',
        'Le pape'
    ];

    public function getDependencies()  

    {
        return [ProgramFixtures::class];  
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        foreach (self::ACTORS as $key => $actorName)
        {
            $actor = new Actor();
            $actor->setName($actorName);
            $actor->addProgram($this->getReference('program_' . rand(1, 6)));
            $manager->persist($actor);
        };

        for ($i = 4; $i <= 54; $i++)
        {
            $actor = new Actor();
            $actor->setName($faker->name);
            $actor->addProgram($this->getReference('program_' . rand(1, 6)));
            $manager->persist($actor);
        }
        $manager->flush();
    }
}
