<?php

namespace App\Command\Game;

use App\Application\Services\Game\GetCurrentGamesService;
use App\Application\Services\Game\GetGameService;
use App\Application\Services\Game\UpdateGameService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateGameCommand extends AbstractGameCommand
{
    protected static $defaultName = 'game:update';

    public function __construct(
        GetCurrentGamesService $getCurrentGamesService,
        private readonly UpdateGameService $updateGameService,
        private readonly GetGameService $getGameService,
    ) {
        parent::__construct($getCurrentGamesService);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $result = $this->displayCurrentGamesList($input, $output);
        if ($result === Command::FAILURE) {
            return Command::FAILURE;
        }

        $gameIdQuestion = $this->askMyQuestion('Please insert game ID you would like to update?');
        $gameIdQuestionResponse = $helper->ask($input, $output, $gameIdQuestion);
        $currentGame = $this->getGameService->handle($gameIdQuestionResponse);
        if (null === $currentGame) {
            $output->writeln(sprintf('<error>Game with %s ID can\'t be found</error>', $gameIdQuestionResponse));
            return Command::FAILURE;
        }

        $homeTeamScoreQuestion = $this->askMyQuestion('Please insert score for HOME team (' . $currentGame->homeTeam . ')?');
        $homeTeamScore = $helper->ask($input, $output, $homeTeamScoreQuestion);

        $awayTeamScoreQuestion = $this->askMyQuestion('Please insert score for AWAY team (' . $currentGame->awayTeam . ')?');
        $awayTeamScore = $helper->ask($input, $output, $awayTeamScoreQuestion);

        $this->updateGameService->handle((int) $gameIdQuestionResponse, (int) $homeTeamScore, (int) $awayTeamScore);
        $output->writeln(sprintf('<info>Game with ID %s successfully removed</info>', $gameIdQuestionResponse));

        $this->displayCurrentGamesList($input, $output);

        return Command::SUCCESS;
    }
}
