<?php

namespace App\Infrastructure\Entity;

class RecipeInstructions
{
    /**
     * @var int
     */
    private $recipeUid;

    /**
     * @var int
     */
    private $step;

    /**
     * @var string
     */
    private $instruction;

    public function __construct(int $recipeUid, int $step, string $instruction)
    {
        $this->recipeUid   = $recipeUid;
        $this->step        = $step;
        $this->instruction = $instruction;
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

    public function getStep(): int
    {
        return $this->step;
    }

    public function setStep(int $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getInstruction(): string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }
}
