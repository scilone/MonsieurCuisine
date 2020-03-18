<?php

use App\Config\Loader;

if (isset($_GET['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    ini_set('log_errors', 'On');
}

require __DIR__ . '/vendor/autoload.php';

$completeUrl = $_SERVER['REQUEST_URI'];
$indexFile   = @end(explode('/', $_SERVER['SCRIPT_FILENAME']));
$indexPath   = $_SERVER['PHP_SELF'];
$basePath    = str_replace($indexFile, '', $indexPath);
$envPath     = str_replace($basePath, '', $completeUrl);

$params = explode('/', parse_url($envPath, PHP_URL_PATH));

if (in_array($params[0] ?? '', ['css', 'img', 'js'])) {
    $asset = __DIR__ . '/assets/' . parse_url($envPath, PHP_URL_PATH);
    if (file_exists($asset)) {
        switch ($params[0] ?? '') {
            case 'css':
                header('content-type: text/css');
                break;
            case 'js':
                header('content-type: application/javascript');
                break;
            default:
                header('Content-Type: ' . mime_content_type($asset));
        }

        echo file_get_contents($asset);
        exit;
    }
}

$controllerBaseFQCN = 'App\Controller\\';
$controllerEndFQCN  = 'Controller';
$controllerPath     = '';
$controllerFQCN     = '';
foreach ($params as $param) {
    $controllerName = ucfirst(array_shift($params));

    $classTest = $controllerBaseFQCN . $controllerName . $controllerEndFQCN;
    if (class_exists($classTest) === true) {
        $controllerFQCN = $classTest;
        break;
    }

    $classTest = $controllerBaseFQCN . $controllerPath . $controllerName . $controllerEndFQCN;
    if (class_exists($classTest) === true) {
        $controllerFQCN = $classTest;
        break;
    }

    $controllerPath .= "$controllerName\\";
}

$action = array_shift($params);

$controller = Loader::getService($controllerFQCN);

if (method_exists($controller, $action) === false || $controller === null) {
    responsePage(404);
}

call_user_func_array([$controller, $action], $params);

function responsePage(int $code): void
{
    http_response_code($code);
    exit;
}
