<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('tc_seconds', $this->formatSeconds(...)),
            new TwigFilter('tc_minutes', $this->formatMinutes(...)),
        ];
    }

    /**
     * Sekunden → "HH:MM:SS" (bzw. "MM:SS" wenn < 1 Stunde).
     *
     * {{ 3661|tc_seconds }}  → "01:01:01"
     * {{ 90|tc_seconds }}    → "01:30"
     * {{ null|tc_seconds }}  → "—"
     */
    public function formatSeconds(?int $seconds): string
    {
        if ($seconds === null) {
            return '—';
        }

        $seconds = abs($seconds);
        $h       = intdiv($seconds, 3600);
        $m       = intdiv($seconds % 3600, 60);
        $s       = $seconds % 60;

        return $h > 0
            ? sprintf('%02d:%02d:%02d', $h, $m, $s)
            : sprintf('%02d:%02d', $m, $s);
    }

    /**
     * Minuten → lesbare Zeitangabe.
     *
     * {{ 45|tc_minutes }}   → "45 Minuten"
     * {{ 90|tc_minutes }}   → "01 Stunde 30 Minuten"
     * {{ 120|tc_minutes }}  → "02 Stunden 00 Minuten"
     * {{ null|tc_minutes }} → "—"
     */
    public function formatMinutes(?int $minutes): string
    {
        if ($minutes === null) {
            return '—';
        }

        $h = intdiv($minutes, 60);
        $m = $minutes % 60;

        if ($h === 0) {
            return sprintf('%d Minuten', $m);
        }

        $hourLabel = $h === 1 ? 'Stunde' : 'Stunden';
        return sprintf('%02d %s %02d Minuten', $h, $hourLabel, $m);
    }
}
