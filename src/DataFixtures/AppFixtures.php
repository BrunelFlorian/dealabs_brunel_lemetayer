<?php

namespace App\DataFixtures;

use App\Entity\Deal;
use App\Entity\DealGroup;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $dealGroups = [];

        for ($i = 0; $i < 5; $i++) {
            $deal = new DealGroup();
            $deal->setName($faker->sentence(1));
            
            $manager->persist($deal);
            
            $dealGroups[] = $deal;
        }
        
        for ($i = 0; $i < 10; $i++) {
            $deal = new Deal();
            $deal->setTitle($faker->sentence(4));
            $deal->setPrice($faker->randomFloat(2, 10, 100));
            $deal->setDescription($faker->paragraph());
            $deal->setNotation($faker->numberBetween(1, 100));
            $deal->setUserCreated(1);
            $createdAt = $faker->dateTimeBetween('-1 year', 'now');
            $deal->setCreatedAt(DateTimeImmutable::createFromMutable($createdAt));
            $expirationDate = $faker->dateTimeBetween('now', '+1 year');
            $deal->setExpirationDate(DateTimeImmutable::createFromMutable($expirationDate));
            $deal->setCategory($faker->randomElement(['Tips', 'Coupon']));
            $deal->setDealGroup($faker->randomElement($dealGroups));

            $manager->persist($deal);
        }

        $manager->flush();
    }
}
