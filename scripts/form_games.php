<?php

$dump = new Dump;

$connect = Database::checkConnect();
$db = $connect->connectDatabase();
$db->query("TRUNCATE TABLE games");

$tableCreate = new TableCreate();

// Получаем список всех игр, Game ID, название игры
$tableCreate->firstGamesData($countryArray);

// Получаем ссылочное название для сайта
$tableCreate->toTranslit();

// Проверяем на наличие игр, которых еще нет в основной таблице
$tableCreate->checkGame();

// Получаем отсортированный массив цен
$tableCreate->sortBestPrice($countryArray);

// Получаем массив отсортированных цен
$priceArray = $tableCreate->getGamesPriceArray();

// Добавляем цены в массив игр
$tableCreate->addPriceToGamesArray($priceArray);

// Получаем остальные данные для игр: ссылка, цена до скидки, наличие скидки, тип скидки, бесплатна ли игра
$tableCreate->getAnotherGameData();

// Расшифровываем страны
$tableCreate->addCountryConvert();

// Заносим полученные данные в БД
$tableCreate->addGamesDB();

// Получаем массив готовых игр
$gamesArray = $tableCreate->getGamesArray();


//$dump->getDump($gamesArray);