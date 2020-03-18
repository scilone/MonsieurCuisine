<?php

namespace App\Domain\Repository;

use App\Infrastructure\Entity;
use App\Infrastructure\SqlConnection;

class RecipeIngredient
{
    private const TABLE_NAME = 'mc_recipe_ingredient';

    /**
     * @var SqlConnection
     */
    private $sqlConnection;

    public function __construct(SqlConnection $sqlConnection)
    {
        $this->sqlConnection = $sqlConnection;
    }

    public function create(Entity\RecipeIngredient $recipe): void
    {
        $mysqli = $this->sqlConnection->get();
        $mysqli->query(
            "INSERT IGNORE INTO " . self::TABLE_NAME . " 
            (`recipe_uid`, `ingredient_id`, `quantity`, `unity`, `raw`) 
            VALUES ('{$recipe->getRecipeUid()}', '{$recipe->getIngredientId()}', '{$recipe->getQuantity()}', '{$recipe->getUnity()}', '{$recipe->getRaw()}');
            "
        );
    }

    public function getAllRecipeUid()
    {
        $mysqli = $this->sqlConnection->get();
        $mysqli->query(
            "SELECT DISTINCT recipe_uid FROM " . self::TABLE_NAME
        );
    }
}
