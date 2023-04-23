<?php

namespace App\Application\Cache;

use App\Application\Dto\GameDto;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

class ScoreboardCache
{
    public const CACHE_KEY = 'scoreboard_data_sr';
    private FilesystemAdapter $cache;

    public function __construct() {
        $this->cache = new FilesystemAdapter();
    }

    public function set(array $data): void
    {
        $item = $this->item();
        $item->set(json_encode($data));
        $this->cache->save($item);
    }

    public function add(GameDto $game): void
    {
        $data = $this->get() ?? [];
        $data[] = (array) $game;

        $item = $this->item();
        $item->set(json_encode($data));
        $this->cache->save($item);
    }

    public function get(): array
    {
        return json_decode($this->item()->get() ?? '', true) ?? [];
    }

    public function clear(): void
    {
        $this->cache->clear(self::CACHE_KEY);
    }

    private function item(): CacheItem
    {
        return $this->cache->getItem(self::CACHE_KEY);
    }
}
