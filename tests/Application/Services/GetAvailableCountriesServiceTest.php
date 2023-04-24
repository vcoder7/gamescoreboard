<?php

namespace App\Tests\Application\Services;

use App\Application\Dto\GameDto;
use App\Application\Services\GetAvailableCountriesService;
use App\Application\Services\GetCurrentGamesService;
use PHPUnit\Framework\TestCase;

class GetAvailableCountriesServiceTest extends TestCase
{
    private GetCurrentGamesService $getCurrentGamesService;
    private GetAvailableCountriesService $testService;

    public function setUp(): void
    {
        $this->getCurrentGamesService = $this->createMock(GetCurrentGamesService::class);

        $this->testService = new GetAvailableCountriesService(
            $this->getCurrentGamesService,
        );
    }

    public function testGivenEmptyFromCurrentGamesServiceThenReturnAllCountries(): void
    {
        $this->getCurrentGamesService->expects($this->once())->method('handle')->willReturn([]);

        $result = $this->testService->handle();

        $this->assertSame(10, count($result));
    }

    public function testGivenFourPlayingTeamsThenReturnSixAvailableTeams(): void
    {
        $games = [
            new GameDto('Italy', 'Germany', 3, 0),
            new GameDto('Canada', 'Mexico', 1, 4)
        ];
        $this->getCurrentGamesService->expects($this->once())->method('handle')->willReturn($games);

        $result = $this->testService->handle();

        $this->assertSame(6, count($result));
    }
}
