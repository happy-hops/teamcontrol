<?php

namespace App\DataFixtures;

use App\Entity\Race;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture implements DependentFixtureInterface
{
    private const TEAMS_ACTIVE = [
        ['name' => 'Scuderia Siegburg',   'lead' => 'Max Müller',   'token' => 'AABBCC11', 'ref' => 'team-active-1'],
        ['name' => 'Schnelle Jungs e.V.', 'lead' => 'Tom Bauer',    'token' => 'DDEEFF22', 'ref' => 'team-active-2'],
        ['name' => 'Team Kaffee & Gas',   'lead' => 'Felix Wagner',  'token' => 'GGHHII33', 'ref' => 'team-active-3'],
        ['name' => 'Die Reifenquäler',    'lead' => 'Jan Fischer',   'token' => 'JJKKLL44', 'ref' => 'team-active-4'],
    ];

    private const TEAMS_PLANNED = [
        ['name' => 'Vollgas Vögel',    'lead' => 'Paul Hoffmann', 'token' => 'MMNNOO55', 'ref' => 'team-planned-1'],
        ['name' => 'Boxengasse Blues', 'lead' => 'Ben Richter',   'token' => 'PPQQRR66', 'ref' => 'team-planned-2'],
    ];

    public function load(ObjectManager $manager): void
    {
        $activeRace  = $this->getReference(RaceFixtures::RACE_ACTIVE_REFERENCE, Race::class);
        $plannedRace = $this->getReference(RaceFixtures::RACE_PLANNED_REFERENCE, Race::class);

        foreach (self::TEAMS_ACTIVE as $data) {
            $team = $this->buildTeam($activeRace, $data);
            $manager->persist($team);
            $this->addReference($data['ref'], $team);
        }

        foreach (self::TEAMS_PLANNED as $data) {
            $team = $this->buildTeam($plannedRace, $data);
            $manager->persist($team);
            $this->addReference($data['ref'], $team);
        }

        $manager->flush();
    }

    private function buildTeam(Race $race, array $data): Team
    {
        $team = new Team($race, $data['name'], $data['lead']);
        (new \ReflectionProperty(Team::class, 'teamToken'))->setValue($team, $data['token']);
        return $team;
    }

    public function getDependencies(): array
    {
        return [RaceFixtures::class];
    }
}
