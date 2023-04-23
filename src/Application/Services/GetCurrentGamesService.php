<?php

namespace App\Application\Services;

use App\Application\Cache\ScoreboardCache;
use App\Application\Dto\GameDto;

class GetCurrentGamesService
{
    public function __construct(
        private readonly ScoreboardCache $scoreboardCache
    ) {
    }

    public function handle(): array
    {
        $currentGames = $this->scoreboardCache->get();

        $games = [];
        if (count($currentGames) < 1) {
            return $games;
        }

        foreach ($currentGames as $game) {
            $games[] = new GameDto(...$game);
        }

        return $games;
    }
}
