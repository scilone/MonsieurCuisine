<?php

namespace App\Domain\Repository;

use App\Infrastructure\Entity;
use App\Infrastructure\SqlConnection;

class Ingredient
{
    private const TABLE_NAME = 'mc_ingredient';

    /**
     * @var SqlConnection
     */
    private $sqlConnection;

    public function __construct(SqlConnection $sqlConnection)
    {
        $this->sqlConnection = $sqlConnection;
    }

    public function create(Entity\Ingredient $ingredient): Entity\Ingredient
    {
        $mysqli = $this->sqlConnection->get();
        $mysqli->query(
            "INSERT IGNORE INTO " . self::TABLE_NAME . " 
            (`name`) 
            VALUES ('{$ingredient->getName()}');
            "
        );

        return $this->hydrateEntity([$ingredient->getName(), $mysqli->insert_id]);
    }

    public function getByName(string $name): ?Entity\Ingredient
    {
        $mysqli = $this->sqlConnection->get();
        $res = $mysqli->query('SELECT name, id FROM ' . self::TABLE_NAME . " WHERE name='$name'");

        if ($res === false) {
            return null;
        }

        $res = $res->fetch_array();

        if ($res === null) {
            return null;
        }

        return $this->hydrateEntity($res);
    }

    private function hydrateEntity(array $rawData): ?Entity\Ingredient
    {
        return new Entity\Ingredient(
            $rawData[0],
            $rawData[1]
        );
    }
}
