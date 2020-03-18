<?php

namespace App\Infrastructure;

class RequestOO
{
    /**
     * @var SuperglobaleParameter
     */
    private $query;

    /**
     * @var SuperglobaleParameter
     */
    private $post;

    /**
     * @var SuperglobaleParameter
     */
    private $cookie;

    public function getQuery(): SuperglobaleParameter
    {
        if ($this->query === null) {
            $this->query = new SuperglobaleParameter($_GET);
        }

        return $this->query;
    }

    public function getPost(): SuperglobaleParameter
    {
        if ($this->post === null) {
            $this->post = new SuperglobaleParameter($_POST);
        }

        return $this->post;
    }

    public function getCookie(): SuperglobaleParameter
    {
        if ($this->cookie === null) {
            $this->cookie = new SuperglobaleParameter($_COOKIE);
        }

        return $this->cookie;
    }
}
