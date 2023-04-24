<?php

namespace App\Command\Game;

use App\Application\Services\GetCurrentGamesService;
use App\Application\Services\GetGameService;
use App\Application\Services\UpdateGameService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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

        $customInputValidation = function ($value) {
            if (preg_match("/[a-z]/i", $value) || !preg_match('/^([0-9]|10|[^\d])$/', $value)) {
                throw new \Exception('Inserted value is not correct, please insert a number between 0 and 10!');
            }

            return $value;
        };

        $gameIdQuestion = new Question('Please insert game ID you would like to update? (Default: 0)');
        $gameIdQuestion->setTrimmable(true);
        $gameIdQuestion->setValidator($customInputValidation);

        $gameIdQuestionResponse = $helper->ask($input, $output, $gameIdQuestion);
        $currentGame = $this->getGameService->handle($gameIdQuestionResponse);
        if (null === $currentGame) {
            $output->writeln(sprintf('<error>Game with %s ID can\'t be found</error>', $gameIdQuestionResponse));
            return Command::FAILURE;
        }

        $homeTeamScoreQuestion = new Question('Please insert score for HOME team (' . $currentGame->homeTeam . ')?');
        $homeTeamScoreQuestion->setTrimmable(true);
        $homeTeamScoreQuestion->setValidator($customInputValidation);
        $homeTeamScore = $helper->ask($input, $output, $homeTeamScoreQuestion);

        $awayTeamScoreQuestion = new Question('Please insert score for AWAY team (' . $currentGame->awayTeam . ')?');
        $awayTeamScoreQuestion->setTrimmable(true);
        $awayTeamScoreQuestion->setValidator($customInputValidation);
        $awayTeamScore = $helper->ask($input, $output, $awayTeamScoreQuestion);

        $this->updateGameService->handle((int) $gameIdQuestionResponse, (int) $homeTeamScore, (int) $awayTeamScore);
        $output->writeln(sprintf('<info>Game with ID %s successfully removed</info>', $gameIdQuestionResponse));

        $this->displayCurrentGamesList($input, $output);

        return Command::SUCCESS;
    }
}
