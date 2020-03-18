<?php

namespace App\Infrastructure;

use DateTime;
use DateTimeImmutable;

class CacheRaw
{
    private const ACTIVE = true;

    /**
     * @var SuperglobalesOO
     */
    private $superglobales;

    /**
     * @var string
     */
    private $prefix;

    /**
     * CacheRaw constructor.
     *
     * @param SuperglobalesOO $superglobales
     * @param string          $prefix
     */
    public function __construct(SuperglobalesOO $superglobales, string $prefix = '')
    {
        $this->superglobales = $superglobales;
        $this->prefix        = $prefix;
    }


    private function isEnable(): bool
    {
        return self::ACTIVE && $this->superglobales->getQuery()->has('force') === false;
    }

    public function set(string $cacheName, string $value): void
    {
        $cacheName = $this->prefix . $cacheName;

        $cache = fopen(
            $cacheName,
            'w+'
        );

        fputs($cache, $value);
        fclose($cache);
    }

    public function get(string $cacheName, string $expire = null): ?string
    {
        if ($this->isEnable() === false) {
            return null;
        }

        $cacheName = $this->prefix . $cacheName;

        if (file_exists($cacheName) === false) {
            return null;
        }

        if ($expire !== null) {
            $dateExpire = DateTime::createFromFormat('U', filemtime($cacheName));
            $dateExpire->modify("+ $expire");

            if ($dateExpire < new DateTimeImmutable()) {
                return null;
            }
        }

        return file_get_contents($cacheName);
    }

    public function delete(string $cacheName): void
    {
        if ($this->isEnable() === false) {
            return;
        }

        $cacheName = $this->prefix . $cacheName;

        unlink($cacheName);
    }
}
