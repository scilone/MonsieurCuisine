<?php

namespace App\Infrastructure;

class CurlOO
{
    private $ch;

    public function init(string $url = null): CurlOO
    {
        $this->ch = curl_init($url);

        return $this;
    }

    /**
     * @param int   $option
     * @param mixed $value
     *
     * @return CurlOO
     */
    public function setOption(int $option, $value): CurlOO
    {
        curl_setopt($this->ch, $option, $value);

        return $this;
    }

    public function     execute()
    {
        return curl_exec($this->ch);
    }
}
