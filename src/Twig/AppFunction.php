<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppFunction extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('topNav', [$this, 'topNav']),
        ];
    }

    public function topNav(string $access): array
    {
        if ($access === '') {
            return [];
        }

        if ($access === 'USER') {
            return [
                'left' => [
                    [
                        'to' => 'driver_index',
                        'name' => 'Fahrer',
                        'icon_before' => 'bi-person',
                    ],
                    [
                        'to' => 'race_index',
                        'name' => 'Rennen',
                        'icon_before' => 'bi-flag',
                    ],
                ],
                'right' => [
                    [
                        'to' => 'logout',
                        'name' => 'Abmelden',
                        'icon_before' => 'bi-box-arrow-right',
                    ],
                ],
                /*[
                    'to' => 'team_index',
                    'name' => 'Teams',
                    'icon_before' => 'bi-people',
                ],
                [
                    'to' => 'event_index',
                    'name' => 'Events',
                    'icon_before' => 'bi-lightning',
                ],
                [
                    'to' => 'penalty_index',
                    'name' => 'Strafen',
                    'icon_before' => 'bi-exclamation-triangle',
                ],*/
            ];
        }
    }
}
