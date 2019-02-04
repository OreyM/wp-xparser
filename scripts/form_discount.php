<?php
$dump = new Dump();

//echo $_REQUEST['urlParsing'];

$getData = ( new SomePage())->getSomePageData($_REQUEST['urlParsing'] );


$allDiscountGameID = implode(' ', $getData);


//$dump->getDump($allDiscountGameID);
//
//
//echo 'GO!';