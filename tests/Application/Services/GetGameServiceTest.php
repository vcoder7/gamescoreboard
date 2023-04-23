<?php

namespace App\Tests\Application\Services;

use App\Application\Cache\ScoreboardCache;
use App\Application\Dto\GameDto;
use App\Application\Services\GetGameService;
use PHPUnit\Framework\TestCase;

class GetGameServiceTest extends TestCase
{
    private ScoreboardCache $scoreboardCache;
    private GetGameService $testService;

    public function setUp(): void
    {
        $this->scoreboardCache = $this->createMock(ScoreboardCache::class);

        $this->testService = new GetGameService($this->scoreboardCache);
    }

    public function testGivenMissingProductThenReturnNull(): void
    {
        $this->scoreboardCache->expects($this->once())->method('get')->willReturn([]);

        $result = $this->testService->handle(234);

        $this->assertNull($result);
    }

    public function testGivenProductThenReturnDtoObject(): void
    {
        $this->scoreboardCache->expects($this->once())->method('get')->willReturn([
            [
                'homeTeam' => 'Germany',
                'homeTeamScore' => 3,
                'awayTeam' => 'Australia',
                'awayTeamScore' => 2,
            ]
        ]);

        $result = $this->testService->handle(0);

        $this->assertInstanceOf(GameDto::class, $result);
        $this->assertSame(3, $result->homeTeamScore);
    }
}
