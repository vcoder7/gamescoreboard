<?php

namespace App\Application\Services;

use App\Application\Cache\ScoreboardCache;
use App\Application\Dto\GameDto;
use App\Exception\GameNotFoundException;

class UpdateGameService
{
    public function __construct(
        private readonly ScoreboardCache $scoreboardCache,
        private readonly GetGameService $getGameService,
        private readonly GetCurrentGamesService $getCurrentGamesService
    ) {
    }

    public function handle(int $gameId, int $homeTeamScoreQuestion, int $awayTeamScoreQuestion): GameDto
    {
        $gameDto = $this->getGameService->handle($gameId);
        if (null === $gameDto) {
            throw new GameNotFoundException('Game not found');
        }

        $currentGames = $this->getCurrentGamesService->handle();

        $gameDto->homeTeamScore = $homeTeamScoreQuestion;
        $gameDto->awayTeamScore = $awayTeamScoreQuestion;

        $currentGames[$gameId] = (array) $gameDto;

        $this->scoreboardCache->set($currentGames);

        return $gameDto;
    }
}
