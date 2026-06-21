<?php

namespace App\DataFixtures;

use App\Entity\Driver;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AttendanceFixtures extends Fixture implements DependentFixtureInterface
{
    private const ASSIGNMENTS = [
        ['team-active-1', 'driver-max',   'TAG000001'],
        ['team-active-1', 'driver-lena',  'TAG000002'],
        ['team-active-1', 'driver-tom',   'TAG000003'],
        ['team-active-2', 'driver-sara',  'TAG000004'],
        ['team-active-2', 'driver-felix', 'TAG000005'],
        ['team-active-2', 'driver-anna',  'TAG000006'],
        ['team-active-3', 'driver-jan',   'TAG000007'],
        ['team-active-3', 'driver-mia',   'TAG000008'],
        ['team-active-3', 'driver-paul',  'TAG000009'],
        ['team-active-4', 'driver-lisa',  'TAG000010'],
        ['team-active-4', 'driver-ben',   'TAG000011'],
        ['team-active-4', 'driver-clara', 'TAG000012'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ASSIGNMENTS as [$teamRef, $driverRef, $tagId]) {
            $this->getReference($teamRef, Team::class)
                 ->registerDriver(
                     $this->getReference($driverRef, Driver::class),
                     $tagId
                 );
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [TeamFixtures::class, DriverFixtures::class];
    }
}
