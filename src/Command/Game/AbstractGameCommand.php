<?php

namespace App\Command\Game;

use App\Application\Services\GetCurrentGamesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractGameCommand extends Command
{
    public function __construct(
        private readonly GetCurrentGamesService $getCurrentGamesService
    ) {
        parent::__construct('abstract command');
    }
    protected function displayCurrentGamesList(InputInterface $input, OutputInterface $output): int
    {
        $currentGames = $this->getCurrentGamesService->handle();
        if (count($currentGames) < 1) {
            $output->writeln('<error>There are no current games</error>');
            return Command::FAILURE;
        }

        $rows = [];
        foreach ($currentGames as $index => $game) {
            $rows[] = [$index, $game->homeTeam . ': ' . $game->homeTeamScore, $game->awayTeam . ': ' . $game->awayTeamScore];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Game ID', 'Home team', 'Away team'])
            ->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
