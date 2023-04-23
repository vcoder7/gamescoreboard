<?php

namespace App\Application\Dto;

class GameDto
{
    public function __construct(
        public string $homeTeam,
        public string $awayTeam,
        public int $homeTeamScore = 0,
        public int $awayTeamScore = 0,
    ) {}
}
