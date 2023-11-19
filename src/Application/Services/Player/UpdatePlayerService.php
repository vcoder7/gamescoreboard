<?php

namespace App\Application\Services\Player;

use App\Entity\Player;
use App\Exception\PlayerNotFoundException;
use App\Repository\PlayerRepository;

class UpdatePlayerService
{
    public function __construct(
        private readonly PlayerRepository $playerRepository
    ) {
    }

    public function handle(int $number): Player
    {
        /** @var Player $player */
        $player = $this->playerRepository->findByNumber($number);
        if (null === $player) {
            throw new PlayerNotFoundException('Player cannot be found');
        }

        $player->setName('Max');
        $player->setNickname('maxi');

        $this->playerRepository->persistAndFlush($player);

        return $player;
    }
}
