<?php

$dump = new Dump();
$startPrasingTime = microtime(true);



#############################
#      Currency Parsing     #
#############################

$startElement = '.fx-top';

$currencyTable = [
    'rus_ru_ru'         => '.pure-table:eq(2) tbody tr:eq(18) td:eq(4)',
    'argentina_es_ar'   => '.pure-table:eq(0) tbody tr:eq(0) td:eq(4)',
    'turkish_tr_tr'     => '.pure-table:eq(2) tbody tr:eq(22) td:eq(4)',
    'brazil_pt_br'      => '.pure-table:eq(0) tbody tr:eq(7) td:eq(4)',
    'columbia_es_co'    => '.pure-table:eq(0) tbody tr:eq(11) td:eq(4)',
    'india_en_in'       => '.pure-table:eq(1) tbody tr:eq(8) td:eq(4)',
    'africa_en_za'      => '.pure-table:eq(4) tbody tr:eq(27) td:eq(4)',
    'mexico_es_mx'      => '.pure-table:eq(0) tbody tr:eq(22) td:eq(4)',
    'newzeland_en_nz'   => '.pure-table:eq(3) tbody tr:eq(10) td:eq(4)',
    'canada_en_ca'      => '.pure-table:eq(0) tbody tr:eq(8) td:eq(4)'
];

$currencyData = new Currency(CURRENCY_URL, $startElement);

$currency = $currencyData->currencyDataResult($currencyTable);

//$dump->getDump($currency);

##############################
#        Parsing game        #
##############################

$sitePage = [
    '/store/top-paid/games/xbox',
    '/store/best-rated/games/xbox',
    '/store/new/games/xbox',
    '/store/top-free/games/xbox'
];

$allGamesPageElements = [
    'fullPage'         => '.context-list-page .m-product-placement-item',
    'titleElement'     => 'h3',
    'productLink'      => '> a',
    'priceElement'     => '.c-price span[itemprop="price"]',
    'newPriceElement'  => '.price-info .c-price .srv_price span',
    'imageElement'     => '.srv_appHeaderBoxArt > img',
    'discountElement'  => '.c-price s'
];

$gamePageElements = [
    'fullPage' => '.product-identity-root',
    'realPrice' => '.context-product-placement-data dl dd:eq(1) > .price-info > .c-price > .price-text > span',
    'freeRealPrice' => '.context-product-placement-data dl dd:eq(1) > .price-info > .c-price > .price-text > .price-disclaimer > span'
];

// Чистим таблицы в базе данных
//foreach ( $countryArray as $tableName => $gameID) {
//    $connect = Database::checkConnect();
//    $db = $connect->connectDatabase();
//    $connect->truncateTable($db, $tableName);
//    $db->close();
//}

// Массив для вывода данных по результату парсинга
$parsingInfo = array();

foreach ($countryArray as $tableName => $countryID) {

    $startCurrentParsingTime = microtime(true);

    $gamesParsing = new Games();

    //Получаем стартовый писок ссылок
    $allGamesUrl = $gamesParsing->getUrls(GAMES_SITE, $sitePage, $countryID);

    //Собираем первичные данные
    $gamesParsing->allGameParsing(GAMES_SITE, $allGamesPageElements, $tableName, true);

    //Получение данных по бесплатным играм
    $freeGamesArray = $gamesParsing->getFreeGamesUrl();
    $gamesParsing->getFreeGames( $freeGamesArray, $tableName, 20 );

    // Получаем данные по новым играм и изображения
    $newGamesArray = $gamesParsing->getNewGame();
    $gamesParsing->getGameImg($gamePageElements, $newGamesArray, 20);

    // Преобразуем валюту
    $gamesParsing->currencyPrice($tableName, $currency);

    // Получаем спарсенные данные
    $readyData = $gamesParsing->getParsingData();

    // Вносим данные в таблицу согласно региону
    $connect = Database::checkConnect();
    $db = $connect->connectDatabase();
    $connect->truncateTable($db, $tableName);
    foreach ($readyData as $toDBData) {
        $connect->insertData($db, $tableName, $toDBData);
    }
    $db->close();

    // Данные для спарсенной инфы
    $country = $gamesParsing->getCountryTable($tableName); // Получаем название страны, которую парсим
    $totalGames = count($readyData); // Получаем кол-во парсеных игр в рамках страны
    $totalTime = microtime(true) - $startCurrentParsingTime;

    $parsingInfo[] = [
        'country' => $country,
        'totalGames' => $totalGames,
        'parsingTime' => $totalTime,
        'newGames' => $newGamesArray
    ];

    unset($gamesParsing);

//    foreach ( $readyData as $data )
//    {
//                    echo "
//                    <div>
//                        <strong style='font-weight: bold;'>Страна: </strong><span>{$data['country']}</span>
//                        <strong style='font-weight: bold;'> | Игра: </strong><span><a href='{$data['game_link']}'>{$data['game_name']}</a></span>
//
//                        <strong style='font-weight: bold;'> | Цена: </strong><span>{$data['game_price']}</span>
//                        <strong style='font-weight: bold;'> | До скидки: </strong><span>{$data['before_discount']}</span>
//                        <strong style='font-weight: bold;'> | Тип скидки: </strong><span>{$data['discount_type']}</span>
//                        <strong style='font-weight: bold;'> | Бесплатно: </strong><span>{$data['free_game']}</span>
//                    </div>
//
//                    ";
//    }
}

// Полное время выполнения парсинга
$finishPArsingTime = microtime(true) - $startPrasingTime;

//$dump->getDump($readyData);