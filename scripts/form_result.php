<?php
$dump = new Dump;

$connect = Database::checkConnect();
$db = $connect->connectDatabase();
$game = array();
$result = $connect->selectData( $db, GAME_TABLE, '*' );
while ( $query = $result->fetch_object() ) {
    $game[$query->game_id] = $query;
}



$db->close();

//$dump->getDump($game);