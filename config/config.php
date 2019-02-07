<?php
ini_set('max_execution_time', '999999');

function autoloadClasses($className) {
    $classPath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'xparser.loc' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . $className . '.php';

    if (file_exists($classPath)) {
        require_once $classPath;
        return true;
    }
    else{
        echo "<p>The called Class - <strong>{$className}</strong> - does not exist!</p>";
    }
    return false;
}

spl_autoload_register('autoloadClasses');

// new Debug();

//DATA to connect to Local DataBase
define('HOST', 'localhost:8889');
define('DATABASE', 'xparser');
define('USERNAME', 'root');
define('PASSWORD', 'root');

// OTHER DB onnect data
require 'wp_conn.php';

define('GAME_TABLE', 'games');

define('VERSION', 'XParser Multi RC3 0.02.04');

//URL Define
define('CURRENCY_URL', 'https://ru.fxexchangerate.com/currency-exchange-rates.html');
define('GAMES_SITE', 'https://www.microsoft.com');

$countryArray = [
    'usa_en_us' => '/en-us',
    'rus_ru_ru' => '/ru-ru',
    'argentina_es_ar' => '/es-ar',
    'turkish_tr_tr' => '/tr-tr',
    'brazil_pt_br' => '/pt-br',
    'columbia_es_co' => '/es-co',
    'india_en_in' => '/en-in',
    'africa_en_za' => '/en-za',
    'mexico_es_mx' => '/es-mx',
    'newzeland_en_nz' => '/en-nz',
    'canada_en_ca' => '/en-ca',
];