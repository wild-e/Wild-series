<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;

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

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
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
                    $slug = new Slugify();
                    $episode->setNumber($i);
                    $episode->setSeason($this->getReference('program_' . $j . '_season_'. $k));
                    $title = $faker->sentence;
                    $episode->setTitle($title);
                    $episode->setSlug($slug->generate($title));
                    $episode->setSynopsis($faker->paragraph);
                    $this->setReference('episode_' . $i, $episode);
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }
}
