<?php

namespace App\Tests\Application\Services\Player;

use App\Application\Services\Player\GetPlayerAvatarStringService;
use App\Application\Tools\StringWrapper;
use App\Entity\Player;
use App\Exception\FileReadException;
use App\Exception\PlayerNotFoundException;
use App\Repository\PlayerRepository;
use PHPUnit\Framework\TestCase;

class GetPlayerAvatarStringServiceTest extends TestCase
{
    private PlayerRepository $playerRepository;
    private StringWrapper $stringWrapper;
    private GetPlayerAvatarStringService $testService;

    public function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->stringWrapper = $this->createMock(StringWrapper::class);

        $this->testService = new GetPlayerAvatarStringService(
            $this->playerRepository,
            $this->stringWrapper
        );
    }

    public function testGivenNonExistingPlayerThenThrowException(): void
    {
        $this->expectException(PlayerNotFoundException::class);

        $this->playerRepository->expects($this->once())->method('findByNumber')->willReturn(null);
        $this->playerRepository->expects($this->never())->method('persistAndFlush');
        $this->stringWrapper->expects($this->never())->method('fileGetContents');

        $this->testService->handle(324, 'tewt');
    }

    public function testGivenFileWithFailuresThenThrowException(): void
    {
        $this->playerRepository->expects($this->once())->method('findByNumber')->willReturn(new Player());
        $this->stringWrapper->expects($this->once())->method('fileGetContents')->willThrowException(new FileReadException('File cannot be read'));

        $result = $this->testService->handle(324, 'some_file_path');

        $this->assertSame('File cannot be read', $result);
    }
}
