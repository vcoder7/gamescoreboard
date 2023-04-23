<?php

namespace App\Command\Game;

use App\Application\Services\GetCurrentGamesService;

class GetAvailableCountriesService
{
    private const COUNTRIES = ['Uruguay', 'Italy', 'Spain', 'Brazil', 'Mexico', 'Canada', 'Argentina', 'Australia', 'Germany', 'France'];

    public function __construct(
        private readonly GetCurrentGamesService $getCurrentGamesService
    ) {
    }

    public function handle(): array
    {
        $currentGames = $this->getCurrentGamesService->handle();
        if (count($currentGames) < 1) {
            return self::COUNTRIES;
        }

        $availableCountries =  [];

    }
}
