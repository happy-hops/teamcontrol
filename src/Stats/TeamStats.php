<?php

namespace App\Stats;

use App\Entity\Attendance;
use App\Enum\RaceMode;
use App\Entity\RaceEvent;
use App\Entity\Team;

/**
 * Port des Ruby Stats-Modells.
 * Berechnet Fahrer- und Team-Statistiken aus den RaceEvents und Turns.
 */
final class TeamStats
{
    /** @var RaceEvent[] nach createdAt sortiert */
    private array $events;

    /** @var array<int, int[]> Runden-Dauern pro Driver-ID */
    private array $turnsByDriver;

    /** @var array<int, int> Strafanzahl pro Driver-ID */
    private array $penaltiesByDriver;

    public function __construct(
        private readonly Team     $team,
        private readonly RaceMode $mode,
    ) {
        // Events nach Zeit sortieren
        $events = $team->attendances
            ->map(fn (Attendance $a) => $a->driver)
            ->reduce(fn () => [], []);

        // Alle Events des Teams sammeln und sortieren
        $allEvents = [];
        foreach ($team->race->teams as $t) {
            if ($t->id !== $team->id) continue;
        }

        // Events direkt aus der Collection des Teams lesen
        // (werden per Doctrine lazy-loaded)
        $this->buildIndexes();
    }

    private function buildIndexes(): void
    {
        // Events nach Zeit sortiert via Doctrine Collection
        $events = $this->team->race->teams->filter(
            fn ($t) => $t->id === $this->team->id
        )->first();

        // Wir bauen die Indexes direkt aus den Attendances auf
        $this->events            = [];
        $this->turnsByDriver     = [];
        $this->penaltiesByDriver = [];

        foreach ($this->team->attendances as $attendance) {
            $driverId = $attendance->driver->id;
            $this->turnsByDriver[$driverId]     = [];
            $this->penaltiesByDriver[$driverId] = 0;
        }
    }

    /**
     * Initialisiert die Stats mit den bereits geladenen Events.
     * Wird vom TeamStatsFactory aufgerufen.
     *
     * @param RaceEvent[] $events
     */
    public function withEvents(array $events): self
    {
        $clone         = clone $this;
        $clone->events = $events;
        usort($clone->events, fn (RaceEvent $a, RaceEvent $b) =>
            $a->createdAt <=> $b->createdAt
        );

        // Turns und Penalties indexieren
        foreach ($clone->events as $event) {
            $driverId = $event->driver->id;

            if ($event->turn !== null) {
                $clone->turnsByDriver[$driverId][]    = $event->turn->duration;
            }

            if ($event->penalty !== null) {
                $clone->penaltiesByDriver[$driverId] = ($clone->penaltiesByDriver[$driverId] ?? 0) + 1;
            }
        }

        return $clone;
    }

    // --- Team-Level ---

    /**
     * Fahrer-ID des aktuell fahrenden Fahrers.
     * Nur im Both-Modus: letzter Arriving-Scan ohne nachfolgendes Leaving vom selben Fahrer.
     */
    public function currentDriverId(): ?int
    {
        if ($this->mode->isLeaving()) {
            return null;
        }

        foreach (array_reverse($this->events) as $event) {
            if (!$event->isArriving()) continue;

            $driverId = $event->driver->id;
            $hasLeaving = false;

            foreach ($this->events as $later) {
                if ($later->createdAt <= $event->createdAt) continue;
                if ($later->driver->id !== $driverId) continue;
                if ($later->isLeaving()) {
                    $hasLeaving = true;
                    break;
                }
            }

            if (!$hasLeaving) {
                return $driverId;
            }
        }

        return null;
    }

    public function totalDriveSeconds(): int
    {
        return array_sum(array_merge(...array_values($this->turnsByDriver)));
    }

    // --- Driver-Level ---

    /**
     * @return array<int, DriverStats> Key = Driver-ID
     */
    public function perDriver(): array
    {
        $currentDriverId = $this->currentDriverId();
        $now             = time();
        $stats           = [];

        foreach ($this->team->attendances as $attendance) {
            $driverId = $attendance->driver->id;
            $isDriving = $driverId === $currentDriverId;

            // Aktuelle Fahrzeit
            $currentDriveSeconds = null;
            if ($isDriving) {
                $arrivingEvent = null;
                foreach (array_reverse($this->events) as $event) {
                    if ($event->driver->id === $driverId && $event->isArriving()) {
                        $arrivingEvent = $event;
                        break;
                    }
                }
                if ($arrivingEvent !== null) {
                    $currentDriveSeconds = $now - $arrivingEvent->createdAt->getTimestamp();
                }
            }

            // Letzte Runde
            $turns              = $this->turnsByDriver[$driverId] ?? [];
            $lastDriveSeconds   = !empty($turns) && !$isDriving ? end($turns) : null;

            // Aktuelle Pause (Zeit seit letztem Leaving dieses Fahrers)
            $lastBreakSeconds = null;
            if (!$isDriving) {
                foreach (array_reverse($this->events) as $event) {
                    if ($event->driver->id === $driverId && $event->isLeaving()) {
                        $lastBreakSeconds = $now - $event->createdAt->getTimestamp();
                        break;
                    }
                }
            }

            $stats[$driverId] = new DriverStats(
                isDriving:           $isDriving,
                currentDriveSeconds: $currentDriveSeconds,
                lastDriveSeconds:    $lastDriveSeconds,
                lastBreakSeconds:    $lastBreakSeconds,
                totalDriveSeconds:   array_sum($turns),
                penaltyCount:        $this->penaltiesByDriver[$driverId] ?? 0,
            );
        }

        return $stats;
    }
}
