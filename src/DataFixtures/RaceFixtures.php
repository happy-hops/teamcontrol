<?php

namespace App\DataFixtures;

use App\Entity\Race;
use App\Enum\RaceMode;
use App\Enum\RaceState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RaceFixtures extends Fixture
{
    public const string RACE_PLANNED_REFERENCE  = 'race-planned';
    public const string RACE_ACTIVE_REFERENCE   = 'race-active';
    public const string RACE_FINISHED_REFERENCE = 'race-finished';

    public function load(ObjectManager $manager): void
    {
        $finished = new Race(
            name:          '24h Kart Siegburg 2024',
            slug:          '24h-kart-siegburg-2024',
            duration:      1440,
            maxDrive:      120,
            maxTurn:       30,
            minTurn:       5,
            breakTime:     15,
            waitingPeriod: 5,
        );
        $finished->scheduled = new \DateTimeImmutable('2024-09-14');
        $this->forceState($finished, RaceState::Finished);
        $manager->persist($finished);
        $this->addReference(self::RACE_FINISHED_REFERENCE, $finished);

        $active = new Race(
            name:          '24h Kart Siegburg 2025',
            slug:          '24h-kart-siegburg-2025',
            duration:      1440,
            maxDrive:      120,
            maxTurn:       30,
            minTurn:       5,
            breakTime:     15,
            waitingPeriod: 5,
        );
        $active->scheduled = new \DateTimeImmutable('today');
        $active->start();
        $manager->persist($active);
        $this->addReference(self::RACE_ACTIVE_REFERENCE, $active);

        $planned = new Race(
            name:          '24h Kart Siegburg 2026',
            slug:          '24h-kart-siegburg-2026',
            duration:      1440,
            maxDrive:      120,
            maxTurn:       30,
            minTurn:       5,
            breakTime:     15,
            waitingPeriod: 5,
        );
        $planned->scheduled      = new \DateTimeImmutable('+1 year');
        $planned->prebookingOpen = true;
        $manager->persist($planned);
        $this->addReference(self::RACE_PLANNED_REFERENCE, $planned);

        $manager->flush();
    }

    private function forceState(Race $race, RaceState $state): void
    {
        (new \ReflectionProperty(Race::class, 'state'))->setValue($race, $state);
    }
}
