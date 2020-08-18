<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Lieux;
use App\Entity\Sorties;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SortieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 3; $i++) {
            $lieux = new Lieux();
            $lieux->setRue($faker->sentence())
                ->setNomLieu($faker->paragraph())
                ->setLatitude($faker->numberBetween())
                ->setLongitude($faker->numberBetween());

            $manager->persist($lieux);

        }




        $manager->flush();
    }
}
