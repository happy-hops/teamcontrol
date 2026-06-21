<?php

namespace App\DataFixtures;

use App\Entity\Driver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DriverFixtures extends Fixture
{
    private const DRIVERS = [
        'driver-max'   => 'Max Müller',
        'driver-lena'  => 'Lena Schmidt',
        'driver-tom'   => 'Tom Bauer',
        'driver-sara'  => 'Sara Weber',
        'driver-felix' => 'Felix Wagner',
        'driver-anna'  => 'Anna Koch',
        'driver-jan'   => 'Jan Fischer',
        'driver-mia'   => 'Mia Meyer',
        'driver-paul'  => 'Paul Hoffmann',
        'driver-lisa'  => 'Lisa Schäfer',
        'driver-ben'   => 'Ben Richter',
        'driver-clara' => 'Clara Wolf',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DRIVERS as $reference => $name) {
            $driver = new Driver($name);
            $manager->persist($driver);
            $this->addReference($reference, $driver);
        }

        $manager->flush();
    }
}
