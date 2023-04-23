<?php

namespace App\Command\Game;

use App\Application\Cache\ScoreboardCache;
use App\Application\Dto\GameDto;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class StartGameCommand extends Command
{
    protected static $defaultName = 'game:start';

    public function __construct(private readonly ScoreboardCache $scoreboardCache)
    {
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $countries = ['Uruguay', 'Italy', 'Spain', 'Brazil', 'Mexico', 'Canada', 'Argentina', 'Australia', 'Germany', 'France'];
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
