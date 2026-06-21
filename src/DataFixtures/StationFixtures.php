<?php

namespace App\DataFixtures;

use App\Entity\Station;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StationFixtures extends Fixture
{
    public const STATION_1_REFERENCE = 'station-1';
    public const STATION_2_REFERENCE = 'station-2';

    private const STATIONS = [
        self::STATION_1_REFERENCE => 'AABBCCDDEEFF',
        self::STATION_2_REFERENCE => '112233445566',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::STATIONS as $reference => $token) {
            $station = new Station($token);
            $manager->persist($station);
            $this->addReference($reference, $station);
        }

        $manager->flush();
    }
}
