<?php

namespace App\Command\Game;

use App\Application\Services\Game\FinishGameService;
use App\Application\Services\Game\GetCurrentGamesService;
use App\Exception\GameNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

        $gameIdQuestion = $this->askMyQuestion('Please insert game ID you would like to finish?');
        $gameIdQuestionResponse = $helper->ask($input, $output, $gameIdQuestion);

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
