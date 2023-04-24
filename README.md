# Football world cup score board

## Intro
- In this project you are able to start/update/finish and list games for defined countries, for more details please read section "Commands"

## Project details:
- To run the project install docker on your computer [docker.com](https://docker.com)
- [Symfony framework](https://symfony.com) 6.2
- PHP version ``8.1``
- Install composer dependencies ``docker-compose exec phpfpm composer install``

## Setup:
- On your local computer ```git clone git@github.com:vcoder7/gamescoreboard.git``` 
- Switch to the project folder and run composer with following command ``docker-compose up -d`` and the docker image will be started in background
- Install composer dependencies ``docker-compose exec phpfpm composer install``

## Commands
- List current games: ```docker-compose exec phpfpm bin/console game:current_games```
- Start a new game: ```docker-compose exec phpfpm bin/console game:start```
- Update a game: ```docker-compose exec phpfpm bin/console game:update```
- Finish a game: ```docker-compose exec phpfpm bin/console game:finish```

## PHP unit tests
- Run PHP unit tests: ```docker-compose exec phpfpm php bin/phpunit```
