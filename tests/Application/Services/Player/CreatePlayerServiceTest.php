<?php

namespace App\Tests\Application\Services\Player;

use App\Application\Services\Player\CreatePlayerService;
use App\Entity\Player;
use App\Exception\PlayerAlreadyExistsException;
use App\Repository\PlayerRepository;
use PHPUnit\Framework\TestCase;

class CreatePlayerServiceTest extends TestCase
{
    private PlayerRepository $playerRepository;
    private CreatePlayerService $testService;

    public function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepository::class);

        $this->testService = new CreatePlayerService(
            $this->playerRepository
        );
    }

    public function testGivenPlayerWithExistingNumberThenThrowException(): void
    {
        $this->expectException(PlayerAlreadyExistsException::class);

        $this->playerRepository->expects($this->once())->method('findByNumber')->willReturn(new Player());
        $this->playerRepository->expects($this->never())->method('persistAndFlush');

        $this->testService->handle(324, 'tewt', 'nicky');
    }

    public function testGivenNonExistingPlayerThenSaveItAndReturnObject(): void
    {
        $this->playerRepository->expects($this->once())->method('findByNumber')->willReturn(null);
        $this->playerRepository->expects($this->once())->method('persistAndFlush');

        $result = $this->testService->handle(55, 'Silvio', '23423432');

        $this->assertSame('Silvio', $result->getName());
        $this->assertSame(55, $result->getNumber());
        $this->assertInstanceOf(Player::class, $result);
    }
}
