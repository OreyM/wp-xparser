<?php
$dump = new Dump();

//echo $_REQUEST['urlParsingRecomend'];


$productID = '';

$curlData = (new Curl($_REQUEST['urlParsingRecomend']))->getCurlData();
$elementParsing = phpQuery::newDocument($curlData);

//var_dump($elementParsing);
//$dump->getDump($elementParsing);
//echo $elementParsing;


foreach ($elementParsing->find('#relateditems_Overview .m-channel-placement .c-carousel ul li') as $parsingData) {

    $parsingData = pq($parsingData);

    $productLink = $parsingData->find('.m-channel-placement-item > a')->attr('href');

    if ( substr($productLink, -5) == 'chart' ) {
        $productLink = str_replace('?cid=msft_web_chart', '', $productLink);
    }

    $productID .= substr($productLink, -12);

    $productID .= ' ';

//    $dump->getDump($parsingData);



//    $productLink = $parsingData->find('a')->attr('href');
//
//    if(substr($productLink, -6) == 'search')
//        $productLink = str_replace('?cid=msft_web_search', '', $productLink);
//
//    $productID = substr($productLink, -12);
//
//    $dataUrl[] = $productID;
}



//$getData = ( new SomePage())->getSomePageData($_REQUEST['urlParsing'] );


//$allDiscountGameID = implode(' ', $getData);


//$dump->getDump($allDiscountGameID);
//
//
//echo 'GO!';