<?php

namespace App\Tests\Application\Services;

use App\Application\Cache\ScoreboardCache;
use App\Application\Dto\GameDto;
use App\Application\Services\GetCurrentGamesService;
use PHPUnit\Framework\TestCase;

class GetCurrentGamesServiceTest extends TestCase
{
    private ScoreboardCache $scoreboardCache;
    private GetCurrentGamesService $testService;

    public function setUp(): void
    {
        $this->scoreboardCache = $this->createMock(ScoreboardCache::class);

        $this->testService = new GetCurrentGamesService($this->scoreboardCache);
    }

    public function testGivenEmptyListThenReturnEmptyResult(): void
    {
        $this->scoreboardCache->expects($this->once())->method('get')->willReturn([]);

        $result = $this->testService->handle();

        $this->assertSame([], $result);
    }

    public function testGivenListWithTreeGamesThenReturnResult(): void
    {
        $games = [
            [
                'homeTeam' => 'Italy',
                'homeTeamScore' => 4,
                'awayTeam' => 'Argentina',
                'awayTeamScore' => 3,
            ], [
                'homeTeam' => 'Mexico',
                'homeTeamScore' => 3,
                'awayTeam' => 'Brazil',
                'awayTeamScore' => 3,
            ], [
                'homeTeam' => 'Germany',
                'homeTeamScore' => 3,
                'awayTeam' => 'Australia',
                'awayTeamScore' => 2,
            ]
        ];
        $this->scoreboardCache->expects($this->once())->method('get')->willReturn($games);

        $result = $this->testService->handle();
        $this->assertEquals(new GameDto(...$games[0]), $result[0]);
    }

}
