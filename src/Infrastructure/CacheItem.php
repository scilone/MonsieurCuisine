<?php

namespace App\Infrastructure;

use DateTime;
use DateTimeImmutable;

class CacheItem
{
    /**
     * @var CacheRaw
     */
    private $cacheRaw;

    /**
     * @var SuperglobalesOO
     */
    private $superglobales;

    public function __construct(CacheRaw $cacheRaw, SuperglobalesOO $superglobales)
    {
        $this->cacheRaw      = $cacheRaw;
        $this->superglobales = $superglobales;
    }


    public function set(string $cacheName, $value): void
    {
        $this->cacheRaw->set($cacheName, serialize($value));
    }

    public function get(string $cacheName, string $expire = null)
    {
        $cacheData = $this->cacheRaw->get($cacheName, $expire);

        if ($cacheData === null) {
            return null;
        }

        return unserialize($cacheData);
    }

    public function delete(string $cacheName): void
    {
        $this->cacheRaw->delete($cacheName);
    }
}
