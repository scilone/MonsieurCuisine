<?php

namespace App\Infrastructure;

class SuperglobaleParameter
{
    public const TYPE_GET     = 'GET';
    public const TYPE_POST    = 'POST';
    public const TYPE_SERVER  = 'SERVER';
    public const TYPE_FILES   = 'FILES';
    public const TYPE_COOKIE  = 'COOKIE';
    public const TYPE_SESSION = 'SESSION';
    public const TYPE_REQUEST = 'REQUEST';
    public const TYPE_ENV     = 'ENV';

    /**
     * @var string
     */
    private $type;

    public function __construct(string $type)
    {
        $this->type = '_' . $type;
    }

    public function all(): array
    {
        return $GLOBALS[$this->type] ?? [];
    }

    public function get(string $key, $defaultValue = null)
    {
        return $GLOBALS[$this->type][$key] ?? $defaultValue;
    }

    public function getInt(string $key, $defaultValue = null): int
    {
        return (int) $this->get($key, $defaultValue);
    }

    public function getBool(string $key, $defaultValue = null): bool
    {
        return (bool) $this->get($key, $defaultValue);
    }

    public function has(string $key)
    {
        return isset($GLOBALS[$this->type][$key]);
    }

    public function set(string $key, $value)
    {
        $GLOBALS[$this->type][$key] = $value;

        return $this;
    }
}
