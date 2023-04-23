<?php

namespace App\Application\Services;

use App\Application\Cache\ScoreboardCache;

class UpdateGameService
{
    public function __construct(
        private readonly ScoreboardCache $scoreboardCache,
        private readonly GetGameService $getGameService,
    ) {
    }

    public function handle(int $gameId, int $homeTeamScoreQuestion, $awayTeamScoreQuestion): bool
    {
        $currentGames = $this->scoreboardCache->get();
        $game = $this->getGameService->handle($gameId);

        $updatedGame = clone $game;
        $updatedGame->homeTeamScore = $homeTeamScoreQuestion;
        $updatedGame->awayTeamScore = $awayTeamScoreQuestion;

        $currentGames[$gameId] = (array) $updatedGame;

        $this->scoreboardCache->set($currentGames);

        return true;
    }
}
