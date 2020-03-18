<?php

namespace App\Application;

use App\Infrastructure\SuperglobalesOO;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Twig
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var SuperglobalesOO
     */
    private $superglobales;

    public function __construct(SuperglobalesOO $superglobalesOO, array $globalVars = [])
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Templates');
        $this->twig = new Environment($loader);
        $this->superglobales = $superglobalesOO;

        foreach ($globalVars as $name => $value) {
            $this->twig->addGlobal($name, $value);
        }

        $this->addGenericVars();
        $this->addGenericFilters();
    }

    private function addGenericFilters(): void
    {

    }

    private function addGenericVars(): void
    {

    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return string
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\LoaderError
     */
    public function render(string $name, array $context = []): string
    {
        return $this->twig->render($name, $context);
    }
}
