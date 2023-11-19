<?php

namespace App\Application\Services\Game;

use App\Application\Cache\ScoreboardCache;
use App\Application\Dto\GameDto;

class GetGameService
{
    public function __construct(
        private readonly ScoreboardCache $scoreboardCache
    ) {
    }

    public function handle(int $gameId): ?GameDto
    {
        $currentGames = $this->scoreboardCache->get();
        if (!isset($currentGames[$gameId])) {
            return null;
        }

        return new GameDto(...$currentGames[$gameId]);
    }
}
