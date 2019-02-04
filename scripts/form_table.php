<?php
require_once 'config/config.php';
$dump = new Dump;

$tableCreate = new TableCreate();

$tableCreate->firstGamesData($countryArray);
$tableCreate->toTranslit();
$gamesArray = $tableCreate->getGamesArray();

$connect = Database::checkConnect();
$db = $connect->connectDatabase();
$connect->truncateTable($db, 'games');
foreach ($gamesArray as $gameData) {
    $connect->insertData($db, 'games', $gameData);
}

$result = $connect->selectData($db, 'games', '*');

while ($du = $result->fetch_object()) {
    echo html_entity_decode($du->game_name) . '<br>';
}

$db->close();

