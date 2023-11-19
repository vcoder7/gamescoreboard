<?php

namespace App\Application\Services\Game;

use App\Application\Cache\ScoreboardCache;
use App\Exception\GameNotFoundException;

class FinishGameService
{
    public function __construct(
        private readonly ScoreboardCache $scoreboardCache,
    ) {
    }

    public function handle(int $gameId): bool
    {
        $currentGames = $this->scoreboardCache->get();
        if (!isset($currentGames[$gameId])) {
            throw new GameNotFoundException('Game can\'t be found!');
        }

        unset($currentGames[$gameId]);
        $this->scoreboardCache->set(array_values($currentGames));

        return true;
    }
}
