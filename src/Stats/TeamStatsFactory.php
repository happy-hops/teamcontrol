<?php

namespace App\Stats;

use App\Entity\Team;
use App\Repository\RaceEventRepository;

/**
 * Lädt alle nötigen Daten und baut ein TeamStats-Objekt.
 * Trennt Datenbankzugriff von der Berechungslogik.
 */
final readonly class TeamStatsFactory
{
    public function __construct(
        private RaceEventRepository $events,
    ) {}

    public function build(Team $team): TeamStats
    {
        $events = $this->events->findByTeam($team);

        return new TeamStats($team, $team->race->mode)
            ->withEvents($events);
    }
}
