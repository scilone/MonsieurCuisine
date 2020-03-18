<?php

namespace App\Config;

Class Service
{
    public const CONTROLLER_IMPORT = 'App\Controller\ImportController';

    public const APPLICATION_TWIG = 'App\Application\Twig';

    public const DOMAIN_REPOSITORY_RECIPE            = 'App\Domain\Repository\Recipe';
    public const DOMAIN_REPOSITORY_RECIPE_INTRUCTION = 'App\Domain\Repository\RecipeInstruction';
    public const DOMAIN_REPOSITORY_RECIPE_INGREDIENT = 'App\Domain\Repository\RecipeIngredient';
    public const DOMAIN_REPOSITORY_INGREDIENT        = 'App\Domain\Repository\Ingredient';

    public const INFRASTRUCTURE_SUPERGLOBALES = 'App\Infrastructure\SuperglobalesOO';
    public const INFRASTRUCTURE_CURL          = 'App\Infrastructure\CurlOO';
    public const INFRASTRUCTURE_CACHE_RAW     = 'App\Infrastructure\CacheRaw';
    public const INFRASTRUCTURE_CACHE_ITEM    = 'App\Infrastructure\CacheItem';
    public const INFRASTRUCTURE_SODIUM        = 'App\Infrastructure\SodiumDummies';
    public const INFRASTRUCTURE_MYSQL         = 'App\Infrastructure\SqlConnection';
}
