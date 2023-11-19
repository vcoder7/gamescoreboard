<?php

namespace App\Command\Game;

use App\Application\Cache\ScoreboardCache;
use App\Application\Dto\GameDto;
use App\Application\Services\Game\GetAvailableCountriesService;
use App\Application\Services\Game\GetCurrentGamesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class StartGameCommand extends AbstractGameCommand
{
    protected static $defaultName = 'game:start';

    public function __construct(
        GetCurrentGamesService $getCurrentGamesService,
        private readonly ScoreboardCache $scoreboardCache,
        private readonly GetAvailableCountriesService $getAvailableCountriesService
    ) {
        parent::__construct($getCurrentGamesService);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $countries = $this->getAvailableCountriesService->handle();
        if (count($countries) < 1) {
            $output->writeln('<info>All teams are playing right now!</info>');
            return Command::FAILURE;
        }

        $helper = $this->getHelper('question');

        $homeTeamSelection = new ChoiceQuestion('Please select home team', $countries);
        $homeTeamSelection->setErrorMessage('Home team %s is not invalid.');
        $homeTeam = $helper->ask($input, $output, $homeTeamSelection);

        $awayTeamSelection = new ChoiceQuestion('Please select away team', array_diff($countries, [$homeTeam]));
        $awayTeamSelection->setErrorMessage('Away team %s is not invalid.');
        $awayTeam = $helper->ask($input, $output, $awayTeamSelection);

        $game = new GameDto($homeTeam, $awayTeam);
        $this->scoreboardCache->add($game);

        $output->writeln(sprintf('Game between %s and %s successfully started', $game->homeTeam, $game->awayTeam));

        return Command::SUCCESS;
    }
}
