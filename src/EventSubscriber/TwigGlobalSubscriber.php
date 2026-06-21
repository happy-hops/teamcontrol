<?php

namespace App\EventSubscriber;

use App\Repository\RaceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

/**
 * Setzt globale Twig-Variablen einmalig pro Request.
 * Läuft nur beim Main-Request — keine Sub-Requests (ESI, forward etc.).
 */
final readonly class TwigGlobalSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Environment    $twig,
        private RaceRepository $races,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->twig->addGlobal('currentRace', $this->races->findCurrent());
        $this->twig->addGlobal('appVersion',  '1.1.0');
    }
}
