<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 30; $i++) {
            $product = new Product();

            $product
                ->setTitle('product' .$i)
                ->setDescription('Juste une petite description'. $i)
                ->setCoverImage('Image de couverture'. $i)
                ->setPrice(mt_rand(400, 8000))
            ;
            $manager->persist($product);
        }
        $manager->flush();



    }
}
