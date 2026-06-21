<?php

namespace App\Factory;

use App\Entity\RaceEvent;
use App\Enum\EventMode;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<RaceEvent>
 */
final class RaceEventFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return RaceEvent::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'createdAt' => self::faker()->dateTime(),
            'driver' => DriverFactory::new(),
            'mode' => self::faker()->randomElement(EventMode::cases()),
            'team' => TeamFactory::new(),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(RaceEvent $raceEvent): void {})
        ;
    }
}
