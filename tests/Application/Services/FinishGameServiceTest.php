<?php

namespace App\Tests\Application\Services;

use App\Application\Cache\ScoreboardCache;
use App\Application\Services\FinishGameService;
use App\Exception\GameNotFoundException;
use PHPUnit\Framework\TestCase;

class FinishGameServiceTest extends TestCase
{
    private ScoreboardCache $scoreboardCache;
    private FinishGameService $testService;

    public function setUp(): void
    {
        $this->scoreboardCache = $this->createMock(ScoreboardCache::class);
        $this->testService = new FinishGameService(
            $this->scoreboardCache
        );
    }

    public function testGivenNonExistingGameThenThrowException(): void
    {
        $this->expectException(GameNotFoundException::class);

        $this->scoreboardCache->expects($this->once())->method('get')->willReturn([['game 1'], ['game 2'], ['game 4']]);
        $this->scoreboardCache->expects($this->never())->method('set');

        $this->testService->handle(3);
    }

    public function testGivenExistingGameThenRemoveItAndReturnTrue(): void
    {
        $this->scoreboardCache->expects($this->once())->method('get')->willReturn([['game 1'], ['game 2'], ['game 4']]);
        $this->scoreboardCache->expects($this->once())->method('set');

        $result = $this->testService->handle(1);

        $this->assertTrue($result);
    }
}
