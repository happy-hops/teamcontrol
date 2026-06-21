<?php

namespace App\Factory;

use App\Entity\Race;
use App\Enum\RaceMode;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Race>
 */
final class RaceFactory extends PersistentObjectFactory
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
        return Race::class;
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
            'breakTime' => self::faker()->randomNumber(),
            'createdAt' => self::faker()->dateTime(),
            'duration' => self::faker()->randomNumber(),
            'maxDrive' => self::faker()->randomNumber(),
            'maxTurn' => self::faker()->randomNumber(),
            'minTurn' => self::faker()->randomNumber(),
            'mode' => self::faker()->randomElement(RaceMode::cases()),
            'name' => self::faker()->text(255),
            'prebookingOpen' => self::faker()->boolean(),
            'slug' => self::faker()->text(255),
            'updatedAt' => self::faker()->dateTime(),
            'waitingPeriod' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Race $race): void {})
        ;
    }
}
