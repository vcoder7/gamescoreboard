<?php

namespace App\Application\Services\Player;

use App\Application\Tools\StringWrapper;
use App\Entity\Player;
use App\Exception\FileReadException;
use App\Exception\PlayerNotFoundException;
use App\Repository\PlayerRepository;

class GetPlayerAvatarStringService
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly StringWrapper $stringWrapper,
    ) {
    }

    public function handle(int $number, string $fullFilePath): string
    {
        /** @var Player $player */
        $player = $this->playerRepository->findByNumber($number);
        if (null === $player) {
            throw new PlayerNotFoundException('Player cannot be found');
        }

        try {
            $content = $this->stringWrapper->fileGetContents($fullFilePath);
        } catch (FileReadException $e) {
            $content = $e->getMessage();
        }

        return $content;
    }
}
