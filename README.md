# Football world cup score board

## Intro
- In this project you are able to start/update/finish and list games for defined countries, for more details please read section "Commands"

## Project details:
- [Symfony framework](https://symfony.com) 6.2
- PHP version ``8.1``

## Setup:
- To run the project install docker on your computer [docker.com](https://docker.com)
- On your local computer ```git clone git@github.com:vcoder7/gamescoreboard.git``` 
- Switch to the project folder and run composer with following command ``docker-compose up -d`` and the docker image will be started in background
- Install composer dependencies ``docker-compose exec phpfpm composer install``

## Commands
- List current games: ```docker-compose exec phpfpm bin/console game:current_games```
- Start a new game: ```docker-compose exec phpfpm bin/console game:start```
- Update a game: ```docker-compose exec phpfpm bin/console game:update```
- Finish a game: ```docker-compose exec phpfpm bin/console game:finish```

## Task
We have players module which has to be maintained through 2 php service classes.
- A new player is created with 3 parameters ``number, name and nickname``.
- An existing player can be updated by its number 

1. We need a new service class for following PhpUnit test```App\Tests\Application\Services\Player\CreatePlayerServiceTest```
2. Existing service class needs to be PhpUnit tested: ```App\Application\Services\Player\UpdatePlayerService```

## PHP unit tests
- Run PHP unit tests: ```docker-compose exec phpfpm php bin/phpunit```
