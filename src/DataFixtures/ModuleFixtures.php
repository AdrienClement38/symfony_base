<?php

namespace App\DataFixtures;

use App\Entity\Module;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class ModuleFixtures extends Fixture
{
    private $createdEntities = [];

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $modules = [];
        for ($i = 0; $i < 6; $i++) {
            $module = new Module();
            $module->setName($faker->word());
            $module->setDescription($faker->paragraph());

            $manager->persist($module);
            $modules[] = $module;
        }

        $manager->flush();

        $this->createdEntities = $modules;
    }

    public function getCreatedEntities(): array
    {
        return $this->createdEntities;
    }
}
