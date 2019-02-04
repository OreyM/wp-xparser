<?php
class Currency {

    private static  $currencyUrl,
                    $generalParsingElement;

    private $currencyData;

    private static $dump;

    public function __construct ($url, $startElement) {
        self::$currencyUrl = $url;
        self::$generalParsingElement = $startElement;
        $this->currencyData = array();

        self::$dump = new Dump();
    }

    private function getSite () {
        $curlData = (new Curl(self::$currencyUrl))->getCurlData();
        $elementParsing = phpQuery::newDocument($curlData);

        return pq($elementParsing->find(self::$generalParsingElement));
    }

    public function currencyDataResult (array $currencyTable) {

        $currencyParcingData = $this->getSite();

        foreach ($currencyTable as $country => $element) {
            $this->currencyData[$country] = (float)$currencyParcingData->find($element)->text();
        }

        return $this->currencyData;
    }
}