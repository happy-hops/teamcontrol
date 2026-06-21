<?php

namespace App\DataFixtures;

use App\Entity\Driver;
use App\Enum\EventMode;
use App\Entity\RaceEvent;
use App\Entity\Team;
use App\Entity\Turn;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TurnAndEventFixtures extends Fixture implements DependentFixtureInterface
{
    private const array TEAM_DRIVERS = [
        'team-active-1' => ['driver-max',   'driver-lena',  'driver-tom'],
        'team-active-2' => ['driver-sara',  'driver-felix', 'driver-anna'],
        'team-active-3' => ['driver-jan',   'driver-mia',   'driver-paul'],
        'team-active-4' => ['driver-lisa',  'driver-ben',   'driver-clara'],
    ];

    public function load(ObjectManager $manager): void
    {
        $raceStart = new \DateTimeImmutable('-3 hours');

        foreach (self::TEAM_DRIVERS as $teamIndex => $driverRefs) {
            $team        = $this->getReference($teamIndex, Team::class);
            $offset      = (int) substr($teamIndex, -1) - 1;
            $currentTime = $raceStart->modify("+{$offset} minutes");
            $driverCount = count($driverRefs);

            for ($round = 0; $round < 8; $round++) {
                $driver = $this->getReference($driverRefs[$round % $driverCount], Driver::class);

                // Arriving
                $arriving = new RaceEvent($team, $driver, EventMode::Arriving);
                $this->forceTimestamp($arriving, $currentTime);
                $manager->persist($arriving);

                // Turn
                $durationSeconds = random_int(15, 28) * 60;
                $leavingTime     = $currentTime->modify("+{$durationSeconds} seconds");

                $turn = new Turn($team, $driver, $durationSeconds);
                $manager->persist($turn);

                // Leaving
                $leaving = new RaceEvent($team, $driver, EventMode::Leaving);
                $leaving->assignTurn($turn);
                $this->forceTimestamp($leaving, $leavingTime);
                $manager->persist($leaving);

                // Pause 15–20 Minuten
                $breakSeconds = random_int(15, 20) * 60;
                $currentTime  = $leavingTime->modify("+{$breakSeconds} seconds");
            }
        }

        $manager->flush();
    }

    private function forceTimestamp(object $entity, \DateTimeImmutable $time): void
    {
        foreach (['createdAt', 'updatedAt'] as $prop) {
            (new \ReflectionProperty($entity::class, $prop))->setValue($entity, $time);
        }
    }

    public function getDependencies(): array
    {
        return [AttendanceFixtures::class];
    }
}
