<?php

namespace App\Domain\Repository;

use App\Infrastructure\Entity;
use App\Infrastructure\SqlConnection;
use DateTimeImmutable;
use DateTimeInterface;

class Recipe
{
    private const TABLE_NAME = 'mc_recipe';

    /**
     * @var SqlConnection
     */
    private $sqlConnection;

    public function __construct(SqlConnection $sqlConnection)
    {
        $this->sqlConnection = $sqlConnection;
    }

    public function create(Entity\Recipe $recipe): void
    {
        $mysqli = $this->sqlConnection->get();
        $mysqli->query(
            "INSERT IGNORE INTO " . self::TABLE_NAME . " 
            (`uid`, `img`, `name`, `url`, `device`, `portion`, 
            `difficulty`, `rate`, `author`, `rate_count`, `id`, `duration`, `duration_total`, `date_create`, `date_update`, `kcal`, `kj`, `protein`, `carbohydrates`, `lipids`) 
            VALUES ('{$recipe->getUid()}', '{$recipe->getImg()}', '{$recipe->getName()}', '{$recipe->getUrl()}', '{$recipe->getDevice()}', '{$recipe->getPortion()}', '{$recipe->getDifficulty()}', '{$recipe->getRate()}', '', '0', NULL, '0', '0', NULL, NULL, '0', '0', '0', '0', '0');
            "
        );
    }

    public function update(Entity\Recipe $recipe): void
    {
        $dateUpdate = $recipe->getUpdate() instanceof DateTimeInterface ? $recipe->getUpdate()->format('Y-m-d') : null;

        $mysqli = $this->sqlConnection->get();
        $mysqli->query(
            "UPDATE `mc_recipe` SET 
            `img` = '{$recipe->getImg()}', 
            `name` = '{$recipe->getName()}', 
            `url` = '{$recipe->getUrl()}',
            `device` = '{$recipe->getDevice()}',
            `portion` = '{$recipe->getPortion()}',
            `difficulty` = '{$recipe->getDifficulty()}', 
            `rate` = '{$recipe->getRate()}', 
            `author` = '{$recipe->getAuthor()}',
            `rate_count` = '{$recipe->getRateCount()}',
            `id` = '{$recipe->getId()}',
            `duration` = '{$recipe->getDuration()}',
            `duration_total` = '{$recipe->getDurationTotal()}', 
            `date_create` = '{$recipe->getCreate()->format('Y-m-d')}', 
            `date_update` = '$dateUpdate', 
            `kcal` = '{$recipe->getKcal()}',
            `kj` = '{$recipe->getKj()}', 
            `protein` = '{$recipe->getProtein()}', 
            `carbohydrates` = '{$recipe->getCarbohydrates()}',
            `lipids` = '{$recipe->getLipids()}'
            WHERE `mc_recipe`.`uid` = {$recipe->getUid()}"
        );
    }

    /**
     * @return Entity\Recipe[]
     */
    public function getAll(): array
    {
        $mysqli = $this->sqlConnection->get();

        $res = $mysqli->query('SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY uid')->fetch_all();

        return array_map([$this, 'hydrateEntity'], $res);
    }

    /**
     * @return Entity\Recipe[]
     */
    public function getAllWhitoutInstructions()
    {
        $mysqli = $this->sqlConnection->get();

        $res = $mysqli->query('SELECT * FROM ' . self::TABLE_NAME . ' WHERE uid NOT IN (SELECT recipe_uid FROM mc_recipe_instructions GROUP BY recipe_uid)')->fetch_all();

        return array_map([$this, 'hydrateEntity'], $res);
    }

    private function hydrateEntity(array $rawData): Entity\Recipe
    {
        return new Entity\Recipe(
            $rawData[0],
            $rawData[1],
            $rawData[2],
            $rawData[3],
            $rawData[4],
            $rawData[5],
            $rawData[6],
            $rawData[7],
            $rawData[8],
            $rawData[9],
            $rawData[10],
            $rawData[11],
            $rawData[12],
            !empty($rawData[13]) ? new DateTimeImmutable($rawData[13]) : null,
            !empty($rawData[14]) ? new DateTimeImmutable($rawData[14]) : null,
            $rawData[15],
            $rawData[16],
            $rawData[17],
            $rawData[18],
            $rawData[19]
        );
    }
}
