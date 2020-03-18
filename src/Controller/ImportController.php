<?php

namespace App\Controller;

use App\Application\Account;
use App\Application\Iptv;
use App\Domain\Repository;
use App\Infrastructure\CurlOO;
use App\Infrastructure\Entity;
use DateTimeImmutable;
use DOMDocument;
use DOMElement;
use DOMXPath;

echo '<pre>';

set_time_limit(3600);
ini_set('memory_limit', '2048M');

class ImportController
{
    private const DEVICE_MC_CONNECT = 'MC connect';
    private const DEVICE_MC_PLUS    = 'MC plus';

    private const DIFFICULTY_EASY   = 'Facile';
    private const DIFFICULTY_MEDIUM = 'Moyen';
    private const DIFFICULTY_HARD   = 'Difficile';

    private const BASE_URL_MC = 'https://www.monsieur-cuisine.com';

    private const MAPPING_DEVICE = [
        self::DEVICE_MC_CONNECT => Entity\Recipe::DEVICE_MC_CONNECT,
        self::DEVICE_MC_PLUS    => Entity\Recipe::DEVICE_MC_PLUS,
    ];

    private const MAPPING_DIFFICULTY = [
        self::DIFFICULTY_EASY   => Entity\Recipe::DIFFICULTY_EASY,
        self::DIFFICULTY_MEDIUM => Entity\Recipe::DIFFICULTY_MEDIUM,
        self::DIFFICULTY_HARD   => Entity\Recipe::DIFFICULTY_HARD,
    ];

    /**
     * @var CurlOO
     */
    private $curl;

    /**
     * @var Repository\Recipe
     */
    private $recipeRepository;

    /**
     * @var Repository\RecipeInstruction
     */
    private $recipeInstructionRepository;

    /**
     * @var Repository\RecipeIngredient
     */
    private $recipeIngredientRepository;

    /**
     * @var Repository\Ingredient
     */
    private $ingredientRepository;

    public function __construct(
        CurlOO $curl,
        Repository\Recipe $recipeRepository,
        Repository\RecipeInstruction $recipeInstructionRepository,
        Repository\RecipeIngredient $recipeIngredientRepository,
        Repository\Ingredient $ingredientRepository
    ) {
        $this->curl                        = $curl;
        $this->recipeRepository            = $recipeRepository;
        $this->recipeInstructionRepository = $recipeInstructionRepository;
        $this->recipeIngredientRepository  = $recipeIngredientRepository;
        $this->ingredientRepository        = $ingredientRepository;
    }

    public function recipeDetail()
    {
        foreach ($this->recipeRepository->getAllWhitoutInstructions() as $recipe) {
            var_dump($recipe->getUid());

            $html = $this->curl
                ->init($recipe->getUrl())
                ->setOption(CURLOPT_RETURNTRANSFER, true)
                ->execute();
            ;

            if (empty($html)) {
                continue;
            }

            libxml_use_internal_errors(true);
            $dom_ban = new domDocument();
            $dom_ban->loadHTML($html);
            $xpath = new DOMXPath($dom_ban);

            $row = $xpath->query(
                "//div[contains(attribute::id, 'recipe-detail')]"
            )->item(0);

            $recipe->setId($row->getAttribute('data-recipe-id'));

            $author = trim(
                str_replace(
                    'Recette de:',
                    '',
                    $this->getElementByTagNameAndClass($row, 'p', 'recipe_author')->nodeValue
                )
            );
            $recipe->setAuthor($author);

            $rateCount = $this->getElementByTagNameAndClass($row, 'span', 'rating-count');
            if (is_object($rateCount)) {
                preg_match('#\d+#', $rateCount->nodeValue, $rateC);
                $recipe->setRateCount(current($rateC));
            }

            $totalDuration = trim(
                str_replace(
                    'Prêt en:',
                    '',
                    $this->getElementByTagNameAndClass($row, 'div', 'recipe-duration-total')->nodeValue
                )
            );
            $recipe->setDurationTotal($this->convertStringToMinutes($totalDuration));

            $totalDuration = trim(
                str_replace(
                    'Temps de préparation:',
                    '',
                    $this->getElementByTagNameAndClass($row, 'div', 'recipe-duration')->nodeValue
                )
            );
            $recipe->setDuration($this->convertStringToMinutes($totalDuration));

            $create = explode(
                '.',
                trim(
                    str_replace(
                        'Créé le:',
                        '',
                        $this->getElementByTagNameAndClass($row, 'div', 'recipe-crdate')->nodeValue
                    )
                )
            );
            if (count($create) === 3) {
                $recipe->setCreate(
                    new DateTimeImmutable("$create[2]-$create[1]-$create[0]")
                );
            }

            $update = explode(
                '.',
                trim(
                    str_replace(
                        'Dernières modifications:',
                        '',
                        $this->getElementByTagNameAndClass($row, 'div', 'recipe-last-edit')->nodeValue
                    )
                )
            );
            if (count($update) === 3) {
                $recipe->setUpdate(
                    new DateTimeImmutable("$update[2]-$update[1]-$update[0]")
                );
            }

            $nutrients = $this->getElementByTagNameAndClass($row, 'div', 'recipe--nutrients')->nodeValue;
            preg_match(
                '#(?<kj>\d+)\s*kj\s*/\s*(?<kcal>\d+)\s*kcalprotéines:\s*(?<protein>\d+)\s*gglucides:\s*(?<carbohydrates>\d+)\s*glipides:\s*(?<lipids>\d+)\s*g#',
                $nutrients,
                $matches
            );

            $recipe->setKj($matches['kj'] ?? 0);
            $recipe->setKcal($matches['kcal'] ?? 0);
            $recipe->setProtein($matches['protein'] ?? 0);
            $recipe->setCarbohydrates($matches['carbohydrates'] ?? 0);
            $recipe->setLipids($matches['lipids'] ?? 0);

            $this->recipeRepository->update($recipe);

            $recipeInstructions = $this->getElementByTagNameAndClass($row, 'div', 'recipe--instructions');

            $i = 0;
            foreach ($recipeInstructions->getElementsByTagName('li') as $li) {
                ++$i;
                $this->recipeInstructionRepository->create(
                    new Entity\RecipeInstructions(
                        $recipe->getUid(),
                        $i,
                        $li->nodeValue
                    )
                );
            }

            $recipeIngredients = $this->getElementByTagNameAndClass($row, 'div', 'recipe--ingredients-html-item');

            foreach ($recipeIngredients->getElementsByTagName('li') as $li) {
                preg_match(
                    "#^(?<quantity>[\d\/½\sà]+)?\s*(?<unity>[kmc]?g|[kmc]?l(?:itre)?|feuille[s]?|c\.[cs]\.|cuil\. à soupe|cuil\. à café|brin[s]?|bâton[s]?|botte[s]?|boule|bouquet|copeaux|cube|filet[s]?|bouquet|fleurette[s]?|flocon[s]?|gousse[s]?|morceau[x]?|pincée[s]?|pulpe[s]?|quartier[s]?|raines|rains|sachet[s]?)?\s(?:d[e\'’’])?\s*(?<ingredient>[a-z\sééêèâ\'àœîï,’ûô]+)#mi",
                    trim($li->nodeValue),
                    $matches
                );

                $quantity   = trim($matches['quantity'] ?? '');
                $unity      = trim($matches['unity'] ?? '');
                $ingredient = trim($matches['ingredient'] ?? '');

                $ingredient = urldecode(
                    str_replace(
                        ['%80', '%99', '%E2'],
                        ['', '', '\''],
                        urlencode($ingredient)
                    )
                );

                $ingredientEntity = $this->ingredientRepository->getByName($ingredient);

                if ($ingredientEntity === null) {
                    $ingredientEntity = $this->ingredientRepository->create(new Entity\Ingredient($ingredient));
                }

                $this->recipeIngredientRepository->create(
                    new Entity\RecipeIngredient(
                        $recipe->getUid(),
                        $ingredientEntity->getId(),
                        $quantity,
                        $unity,
                        trim($li->nodeValue)
                    )
                );
            }
        }
    }

    private function convertStringToMinutes(string $string): int
    {
        preg_match('#(?<hours>\d+):(?<minutes>\d+) h#', $string, $matches);

        if (isset($matches['hours'])) {
            return ($matches['hours'] * 60) + $matches['minutes'];
        }

        return (int) $string;
    }

    public function recipeList()
    {
        for ($i = 0; $i < 1300; $i += 12) {
            $html = $this->curl
                ->init(self::BASE_URL_MC . "/nc/fr/recherche/term/*/start/$i/")
                ->setOption(CURLOPT_RETURNTRANSFER, true)
                ->execute();

            libxml_use_internal_errors(true);
            $dom_ban = new domDocument();
            $dom_ban->loadHTML($html);
            $xpath = new DOMXPath($dom_ban);

            $rows = $xpath->query(
                "//div[contains(attribute::class, 'recipe-result')]"
            );

            /** @var DOMElement $cols */
            foreach ($rows as $cols) {
                $img = $cols->getElementsByTagName('img')->item(0)->getAttribute('src');

                $infoEl = $this->getElementByTagNameAndClass($cols, 'a', 'recipe-card-link');

                $name = $infoEl->nodeValue;
                $url  = $infoEl->getAttribute('href');

                $uid = $this
                    ->getElementByTagNameAndClass($cols, 'a', 'toggle-recipe-fav')
                    ->getAttribute('data-recipe-uid');

                $device = $this
                    ->getElementByTagNameAndClass($cols, 'span', 'device-version-text')
                    ->nodeValue;

                $portion = trim(
                    $this
                        ->getElementByTagNameAndClass($cols, 'div', 'recipe-portions')
                        ->nodeValue
                );

                $difficulty = trim(
                    $this
                        ->getElementByTagNameAndClass($cols, 'div', 'recipe-difficulty')
                        ->nodeValue
                );

                $ratingEl = $this->getElementByTagNameAndClass($cols, 'div', 'rating');

                $rating = 0;
                foreach ($ratingEl->getElementsByTagName('span') as $rate) {
                    switch ($rate->getAttribute('class')) {
                        case 'mc-star_filled':
                            $rating += 1;
                            break;
                        case 'mc-half_star_thick':
                            $rating += .5;
                            break;
                        default:
                            break;
                    }
                }

                $recipe = new Entity\Recipe(
                    $uid,
                    self::BASE_URL_MC . $img,
                    $name,
                    self::BASE_URL_MC . $url,
                    self::MAPPING_DEVICE[$device] ?? 0,
                    $portion,
                    self::MAPPING_DIFFICULTY[$difficulty] ?? 0,
                    $rating
                );

                $this->recipeRepository->create($recipe);
            }
        }
    }

    private function getElementByTagNameAndClass(DOMElement $domElement, string $tagName, string $className): ?DOMElement
    {
        $elements = $domElement->getElementsByTagName($tagName);
        foreach ($elements as $element) {
            $classAttr = $element->getAttribute('class');
            if (empty($classAttr)) {
                continue;
            }

            $classes = explode(' ', $classAttr);

            if (is_array($classes)) {
                foreach ($classes as $class) {
                    if ($class === $className) {
                        return $element;
                    }
                }
            } elseif ($classes === $className) {
                return $element;
            }
        }

        return null;
    }
}
