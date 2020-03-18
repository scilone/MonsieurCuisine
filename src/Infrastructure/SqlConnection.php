<?php

namespace App\Infrastructure;

use mysqli;

class SqlConnection
{
    /**
     * @var mysqli
     */
    private $mysqli;

    public function __construct(
        string $host = null,
        string $username = null,
        string $password = null,
        string $database = null,
        int $port = null,
        string $socket = null
    ) {
        $this->mysqli = new mysqli($host, $username, $password, $database, $port, $socket);
    }

    public function get(): mysqli
    {
        return $this->mysqli;
    }
}
