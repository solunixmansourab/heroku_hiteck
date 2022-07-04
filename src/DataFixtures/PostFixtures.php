<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements FixtureGroupInterface
{

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 1; $i < 21; $i ++) {
            $post = new Post();

            $post->setTitle('What is Lorem Ipsum'. $i)
                ->setContent('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy.'. $i)
                ->setIsPublished(true)
                ->setExcerpt(substr($post->getContent(), 0, 78))
            ;

            $manager->persist($post);
        }

        $manager->flush();
    }

    public static function getGroups() : array
    {
        return ['group2'];
    }
}
