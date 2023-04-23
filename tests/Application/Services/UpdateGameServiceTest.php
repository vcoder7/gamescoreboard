<?php

namespace App\Tests\Application\Services;

use App\Application\Cache\ScoreboardCache;
use App\Application\Dto\GameDto;
use App\Application\Services\GetCurrentGamesService;
use App\Application\Services\GetGameService;
use App\Application\Services\UpdateGameService;
use App\Exception\GameNotFoundException;
use PHPUnit\Framework\TestCase;

class UpdateGameServiceTest extends TestCase
{
    private ScoreboardCache $scoreboardCache;
    private GetGameService $getGameService;
    private GetCurrentGamesService $getCurrentGamesService;
    private UpdateGameService $testService;

    public function setUp(): void
    {
        $this->scoreboardCache = $this->createMock(ScoreboardCache::class);
        $this->getGameService = $this->createMock(GetGameService::class);
        $this->getCurrentGamesService = $this->createMock(GetCurrentGamesService::class);

        $this->testService = new UpdateGameService(
            $this->scoreboardCache,
            $this->getGameService,
            $this->getCurrentGamesService,
        );
    }

    public function testGivenWrongGameThenThrowException(): void
    {
        $this->expectException(GameNotFoundException::class);

        $this->getGameService->expects($this->once())->method('handle')->willReturn(null);

        $this->scoreboardCache->expects($this->never())->method('set');

        $this->testService->handle(234, 1, 1);
    }

    public function testGivenExistingGameThenUpdateIt(): void
    {
        $games = [
            new GameDto('Italy', 'Germany', 3, 0),
            new GameDto('Canada', 'Mexico', 1, 4)
        ];
        $this->getGameService->expects($this->once())->method('handle')->willReturn(new GameDto('Canada', 'Mexico'));
        $this->getCurrentGamesService->expects($this->once())->method('handle')->willReturn($games);

        $this->scoreboardCache->expects($this->once())->method('set');

        $result = $this->testService->handle(1, 1, 5);

        $this->assertInstanceOf(GameDto::class, $result);
        $this->assertSame(5, $result->awayTeamScore);
    }
}
