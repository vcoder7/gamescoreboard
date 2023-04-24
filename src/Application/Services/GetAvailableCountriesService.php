<?php

namespace App\Application\Services;

use App\Application\Dto\GameDto;

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

        $playingTeams =  [];

        /* @var GameDto $game */
        foreach ($currentGames as $game) {
            $playingTeams[] = $game->homeTeam;
            $playingTeams[] = $game->awayTeam;
        }

        return array_diff(self::COUNTRIES, $playingTeams);
    }
}
