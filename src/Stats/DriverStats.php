<?php

namespace App\Stats;

/**
 * Berechnete Statistiken für einen einzelnen Fahrer innerhalb eines Teams.
 * Immutable Value Object — wird von TeamStats erzeugt.
 */
final readonly class DriverStats
{
    public function __construct(
        /** Aktuell am Steuer? Nur im Both-Modus möglich. */
        public bool $isDriving,

        /** Sekunden seit dem letzten Arriving-Scan; null wenn nicht am Steuer */
        public ?int $currentDriveSeconds,

        /** Dauer der letzten abgeschlossenen Runde in Sekunden */
        public ?int $lastDriveSeconds,

        /** Sekunden seit dem letzten Leaving-Scan (= aktuelle Pause) */
        public ?int $lastBreakSeconds,

        /** Gesamte Fahrzeit in Sekunden */
        public int  $totalDriveSeconds,

        /** Anzahl Strafen */
        public int  $penaltyCount,
    ) {}
}
