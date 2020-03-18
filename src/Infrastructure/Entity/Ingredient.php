<?php

namespace App\Infrastructure\Entity;

class Ingredient
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name, int $id = null)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
