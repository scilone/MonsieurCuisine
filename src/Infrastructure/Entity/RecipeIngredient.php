<?php

namespace App\Infrastructure\Entity;

class RecipeIngredient
{
    /**
     * @var int
     */
    private $recipeUid;

    /**
     * @var int
     */
    private $ingredientId;

    /**
     * @var string
     */
    private $quantity;

    /**
     * @var string
     */
    private $unity;

    /**
     * @var string
     */
    private $raw;

    public function __construct(int $recipeUid, int $ingredientId, string $quantity, string $unity, string $raw)
    {
        $this->recipeUid    = $recipeUid;
        $this->ingredientId = $ingredientId;
        $this->quantity     = $quantity;
        $this->unity        = $unity;
        $this->raw          = $raw;
    }

    public function getRecipeUid(): int
    {
        return $this->recipeUid;
    }

    public function setRecipeUid(int $recipeUid): self
    {
        $this->recipeUid = $recipeUid;

        return $this;
    }

    public function getIngredientId(): int
    {
        return $this->ingredientId;
    }

    public function setIngredientId(int $ingredientId): self
    {
        $this->ingredientId = $ingredientId;

        return $this;
    }

    public function getQuantity(): string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnity(): string
    {
        return $this->unity;
    }

    public function setUnity(string $unity): self
    {
        $this->unity = $unity;

        return $this;
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function setRaw(string $raw): self
    {
        $this->raw = $raw;

        return $this;
    }
}
