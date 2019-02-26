<?php

class SomePage {

    public function getSomePageData ($url) {

        $dataUrl = array();

        $curlData = (new Curl($url))->getCurlData();
        $elementParsing = phpQuery::newDocument($curlData);

        foreach ($elementParsing->find('table tr td') as $parsingData) {
//            '#ContentBlockList_1 .gameDivsWrapper .m-product-placement-item' - MS XBOX
//            'table tr td' - XBOX

            $parsingData = pq($parsingData);


            $productLink = $parsingData->find('a')->attr('href');

            echo $productLink . ' => ';

            if ( substr( $productLink, -5 ) == 'chart' )
                $productLink = str_replace('?cid=msft_web_chart', '', $productLink);
            if ( substr( $productLink, -6 ) == 'search' )
                $productLink = str_replace('?cid=msft_web_search', '', $productLink);

            echo $productLink . '<br>';

            $productID = substr($productLink, -12);

            $dataUrl[] = $productID;
        }
        return $dataUrl;
    }
}