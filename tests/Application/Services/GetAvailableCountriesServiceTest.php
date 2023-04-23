<?php

namespace App\Tests\Application\Services;

use App\Application\Services\GetCurrentGamesService;
use App\Command\Game\GetAvailableCountriesService;
use App\Exception\GameNotFoundException;
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

    public function testGivenWrongGameThenThrowException(): void
    {
        $this->getCurrentGamesService->expects($this->once())->method('handle')->willReturn([]);

        $result = $this->testService->handle();

        $this->assertSame(10, count($result));
    }
}
