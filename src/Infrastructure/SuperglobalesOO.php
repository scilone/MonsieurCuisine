<?php

namespace App\Infrastructure;

class SuperglobalesOO
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

    /**
     * @var SuperglobaleParameter
     */
    private $session;

    /**
     * @var SuperglobaleParameter
     */
    private $server;

    public function getQuery(): SuperglobaleParameter
    {
        if ($this->query === null) {
            $this->query = new SuperglobaleParameter(SuperglobaleParameter::TYPE_GET);
        }

        return $this->query;
    }

    public function getPost(): SuperglobaleParameter
    {
        if ($this->post === null) {
            $this->post = new SuperglobaleParameter(SuperglobaleParameter::TYPE_POST);
        }

        return $this->post;
    }

    public function getCookie(): SuperglobaleParameter
    {
        if ($this->cookie === null) {
            $this->cookie = new SuperglobaleParameter(SuperglobaleParameter::TYPE_COOKIE);
        }

        return $this->cookie;
    }

    public function getSession(): SuperglobaleParameter
    {
        if (session_id() === '') {
            session_start();
        }

        if ($this->session === null) {
            $this->session = new SuperglobaleParameter(SuperglobaleParameter::TYPE_SESSION);
        }

        return $this->session;
    }

    public function getServer(): SuperglobaleParameter
    {
        if ($this->server === null) {
            $this->server = new SuperglobaleParameter(SuperglobaleParameter::TYPE_SERVER);
        }

        return $this->server;
    }
}
