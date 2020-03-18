<?php

namespace App\Domain\Repository;

use App\Infrastructure\Entity;
use App\Infrastructure\SqlConnection;

class RecipeInstruction
{
    private const TABLE_NAME = 'mc_recipe_instructions';

    /**
     * @var SqlConnection
     */
    private $sqlConnection;

    public function __construct(SqlConnection $sqlConnection)
    {
        $this->sqlConnection = $sqlConnection;
    }

    public function create(Entity\RecipeInstructions $recipeInstructions): void
    {
        $mysqli = $this->sqlConnection->get();
        $mysqli->query(
            "INSERT IGNORE INTO " . self::TABLE_NAME . " 
            (`recipe_uid`, `step`, `instruction`) 
            VALUES ('{$recipeInstructions->getRecipeUid()}', '{$recipeInstructions->getStep()}', '{$recipeInstructions->getInstruction()}');
            "
        );
    }
}
