<?php

namespace App\DataFixtures;

use App\Entity\Deal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $deal = new Deal();
            $deal->setTitle($faker->sentence(4));
            $deal->setPrice($faker->randomFloat(2, 10, 100));
            $deal->setDescription($faker->paragraph());
            $deal->setNotation($faker->numberBetween(1, 100));
            $deal->setUserCreated(1);
            $deal->setDate($faker->dateTimeBetween('-1 year', 'now'));
            $deal->setExpirationDate($faker->dateTimeBetween('now', '+1 year'));
            $deal->setCategory($faker->randomElement(['Tips', 'Coupon code']));

            $manager->persist($deal);
        }

        $manager->flush();
    }
}
