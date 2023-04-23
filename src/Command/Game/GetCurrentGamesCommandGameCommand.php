<?php

namespace App\Command\Game;

use App\Application\Services\GetCurrentGamesService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCurrentGamesCommand extends GameCommandAbstract
{
    protected static $defaultName = 'game:current_games';

    public function __construct(
        GetCurrentGamesService $getCurrentGamesService,
    ) {
        parent::__construct($getCurrentGamesService);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->displayCurrentGamesList($input, $output);
    }
}
