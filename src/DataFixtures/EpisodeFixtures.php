<?php

namespace App\DataFixtures;

use App\Entity\Episode;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()  

    {
        return [SeasonFixtures::class];  
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($j = 1; $j <= 6; $j++)
        {
            for ($k = 1; $k <= 10; $k++)
            {
                for ($i = 1; $i <= 21; $i++)
                {
                    $episode = new Episode();
                    $episode->setNumber($i);
                    $episode->setSeason($this->getReference('program_' . $j . '_season_'. $k));
                    $episode->setTitle($faker->sentence);
                    $episode->setSynopsis($faker->paragraph);
                    $this->setReference('episode_' . $i, $episode);
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }
}
