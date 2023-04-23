<?php

namespace App\Command\Game;

use App\Application\Services\GetCurrentGamesService;
use App\Application\Services\GetGameService;
use App\Application\Services\UpdateGameService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UpdateGameCommand extends GameCommandAbstract
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

        $gameIdQuestionResponse = $helper->ask($input, $output, new Question('Please insert game ID you would like to update? (Default: 0)'));
        $currentGame = $this->getGameService->handle($gameIdQuestionResponse);
        if (null === $currentGame) {
            $output->writeln(sprintf('<error>Game with %s ID can\'t be found</error>', $gameIdQuestionResponse));
            return Command::FAILURE;
        }

        $homeTeamScoreQuestion = $helper->ask($input, $output, new Question('Please insert score for HOME team (' . $currentGame->homeTeam . ')?'));
        $awayTeamScoreQuestion = $helper->ask($input, $output, new Question('Please insert score for AWAY team (' . $currentGame->awayTeam . ')?'));

        $this->updateGameService->handle((int) $gameIdQuestionResponse, (int) $homeTeamScoreQuestion, (int) $awayTeamScoreQuestion);
        $output->writeln(sprintf('<info>Game with ID %s successfully removed</info>', $gameIdQuestionResponse));

        $this->displayCurrentGamesList($input, $output);

        return Command::SUCCESS;
    }
}
