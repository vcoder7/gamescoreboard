<?php

namespace App\Command\Game;

use App\Application\Services\FinishGameService;
use App\Application\Services\GetCurrentGamesService;
use App\Exception\GameNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class FinishGameCommand extends AbstractGameCommand
{
    protected static $defaultName = 'game:finish';

    public function __construct(
        GetCurrentGamesService $getCurrentGamesService,
        private readonly FinishGameService $finishGameService
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

        $gameIdQuestionResponse = $helper->ask($input, $output, new Question('Please insert game ID you would like to finish? (Default: 0)'));

        try {
            $this->finishGameService->handle((int) $gameIdQuestionResponse);
        } catch (GameNotFoundException $e) {
            $output->writeln(sprintf('<error>Game with %s ID can\'t be found</error>', $gameIdQuestionResponse));
            return Command::FAILURE;
        }

        $output->writeln(sprintf('<info>Game with ID %s successfully removed</info>', $gameIdQuestionResponse));

        $this->displayCurrentGamesList($input, $output);

        return Command::SUCCESS;
    }

}
