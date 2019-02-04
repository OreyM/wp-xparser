<?php
class Games {

    private $allGamesUrl,
            $gamesData,
            $gamesArray,
            $freeGamesUrl,
            $newGameUrl;

    protected static $dump;

    public function __construct() {
        $this->allGamesUrl = array();
        $this->gamesData = array();
        $this->gamesArray = array();
        $this->freeGamesUrl = array();

        self::$dump = new Dump();
    }

    public function getUrls ( $gamesSite, array $sitePage, $countryID ) {

        foreach ( $sitePage as $pageType ) {
            $this->allGamesUrl[] = $gamesSite . $countryID . $pageType;
        }

        return $this->allGamesUrl;
    }

    private function getAfterCurlData (array $urls) {
        $curlData = ( new MultiCurl($urls) )->getData();

        //Очищаем список ссылок
        $this->allGamesUrl = array();

        return $curlData;
    }

    protected function transformPrice($price, $countryID){

        $price = htmlentities($price);

        if ( $countryID === 'rus_ru_ru' )
        {
            $price = preg_replace('/[^0-9,.$]/', '', $price);
        }
        else
        {
            $price = preg_replace('/[^0-9,.]/', '', $price);
        }

        if ( $price[0] == '$' )
        {
            return $price;
        }

        if ( $countryID === 'rus_ru_ru' )
        {
                $price = str_replace(',', '.', $price);
        }

        if ( $countryID === 'brazil_pt_br' || $countryID === 'africa_en_za' ||
             $countryID === 'turkish_tr_tr' )
            $price = str_replace(',', '.', $price);

        if ( $countryID === 'argentina_es_ar' || $countryID === 'columbia_es_co' ){
            $price = str_replace('.', '', $price);
            $price = str_replace(',', '.', $price);
        }

        if ( $countryID === 'india_en_in' || $countryID === 'mexico_es_mx') {
            $price = str_replace(',', '', $price);
        }

        $cleanPrice = (float)$price;

        return $cleanPrice;
    }

    protected function getFreeGame ($price) {
        $freeGame = FALSE;
        if ( round($price, 2) === 0.0) {
            $freeGame = TRUE;
        }

        return $freeGame;
    }

    protected function getDiscountType (phpQueryObject $parsingData, $countryID) {
        if ( $countryID === 'turkish_tr_tr' ) {
            $discountType = trim($parsingData->find('.c-price > span > span:first')->text());
            if ( $discountType === 'Şimdi' || $discountType === 'Dâhil') {
                $discountType = 'EA Access';
            }
        } else {
            // Another country
            $discountType = trim($parsingData->find('.c-price > span > span:last')->text());
        }

        if ( !$discountType ) {
            $discountType = 'Discount';
        }

        return $discountType;
    }

    protected function getCountryName ($countryTableID) {
        switch ($countryTableID) {
            case 'usa_en_us':
                return 'USA';
            case 'rus_ru_ru':
                return 'Russia';
            case 'argentina_es_ar':
                return 'Argentina';
            case 'brazil_pt_br':
                return 'Brazil';
            case 'canada_en_ca':
                return 'Canada';
            case 'columbia_es_co':
                return 'Colombia';
            case 'india_en_in':
                return 'India';
            case 'africa_en_za':
                return 'South Africa';
            case 'turkish_tr_tr':
                return 'Turkey';
            case 'mexico_es_mx':
                return 'Mexico';
            case 'newzeland_en_nz':
                return 'New Zealand';
        }
    }

    public function allGameParsing ($gamesSite, array $pageElement, $countryID, $parsNextPage = FALSE) {

        // Определяем страну
        $productCountry = $this->getCountryName($countryID);

        // Парсим полученные ссылки
        $processingData = $this->getAfterCurlData($this->allGamesUrl);

        foreach ($processingData as $individualData) {

            $domStruct = phpQuery::newDocument($individualData);

            foreach ($domStruct->find('.context-list-page .m-channel-placement-item') as $parsingData) {

                $parsingData = pq($parsingData);

                $productLink = $gamesSite . ( $parsingData->find($pageElement['productLink'])->attr('href') );
                if ( substr($productLink, -5) == 'chart' ) {
                    $productLink = str_replace('?cid=msft_web_chart', '', $productLink);
                }

                $productID = substr($productLink, -12);

                if ( !isset( $this->gamesData[$productID] ) ) {
                    $productTitle = $parsingData->find($pageElement['titleElement'])->text();

                    $productPrice = $this->transformPrice(trim($parsingData->find($pageElement['priceElement'])->text()), $countryID);

                    $productBeforeDiscountPrice = $this->transformPrice(trim($parsingData->find($pageElement['discountElement'])->text()), $countryID);

                    // Поиск бесплатных игр
                    $productFreeGame = $this->getFreeGame($productPrice);
                    if ($productFreeGame) {
                        $this->freeGamesUrl[$productID] = $productLink;
                    }

                    // Определение типа скидки
                    $discount = FALSE;
                    $discountType = '';
                    if ( round($productPrice, 2) < round($productBeforeDiscountPrice, 2) || $productFreeGame) {
                        $discount = TRUE;
                        $discountType = $this->getDiscountType($parsingData, $countryID);
                    }

                    // Поиск новых игр
                    $filename = 'images/game_img/' . $productID . '/' . $productID . '-prewie.jpg';
                    if (!file_exists($filename)){
                        $this->newGameUrl[$productTitle] = $productLink;
                    }

                    #Вносим полученные данные в массив
                    $someGameArray = [
                        'country'           => $productCountry,
                        'game_id'           => $productID,
                        'game_name'         => $productTitle,
                        'game_link'         => $productLink,
                        'game_price'        => $productPrice,
                        'before_discount'   => $productBeforeDiscountPrice,
                        'discount'          => $discount,
                        'discount_type'     => $discountType,
                        'free_game'         => $productFreeGame,
                        'recommend'         => ''
                    ];
                    $this->gamesArray[$productID] = $someGameArray;
                }
            }

            #Получаем ссылку на следующую страницу
            $nextPageUrl = $domStruct->find('.m-pagination > .f-active')->next('')->find('a')->attr('href');
            #ПРоверяем что бы ссылка не заканчивалась на -1
            $checkCorrectUrl = substr($nextPageUrl, -2);
            #Если ссылка не пустая и не заканчиваеться на -1, вносим ее в массив ссылок для MultiCurl
            if(!empty($nextPageUrl) && $checkCorrectUrl != -1){
                $this->allGamesUrl[] = $gamesSite . $nextPageUrl;
            }
        }

        # Если полученный массив ссылок не пустой
        # и парсинг остальных сылок разрешен $parsNextPage === TRUE
        # рекурсируем метод allGameParsing
        if( !empty($this->allGamesUrl) && $parsNextPage ) {
            $this->allGameParsing ( $gamesSite, $pageElement, $countryID, TRUE );
        }
    }

    public function getFreeGamesUrl () {
        return $this->freeGamesUrl;
    }

    public function getFreeGames (array $freeGamesUrls, $countryID, $iterations) {

        for($i = 0; $i < $iterations; ++$i){
            if(!empty($freeGamesUrls))
                $curlUrls[] = array_shift($freeGamesUrls);
            else
                break;
        }

        if(!empty($curlUrls)) {

            $curlData = $this->getAfterCurlData($curlUrls);

            foreach ($curlData as $url => $somePage){
                $elementParsing = phpQuery::newDocument($somePage);
                $gameID = substr($url, -12);

                foreach ($elementParsing->find('#purchaseColumn') as $parsingData) {

                    $parsingData = pq($parsingData);

                    $productPrice = $this->transformPrice(trim($parsingData->find('#productPrice span')->text()), $countryID);

                    $this->gamesArray[$gameID]['before_discount'] = $productPrice;

                }
            }
        }

        if(!empty($freeGamesUrls))
            $this->getFreeGames($freeGamesUrls, $countryID, $iterations);
    }

    public function getNewGame () {
        return $this->newGameUrl;
    }

    public function getGameImg (array $gamePageElements, $newGameUrls, $iterations) {

        for($i = 0; $i < $iterations; ++$i){
            if(!empty($newGameUrls))
                $curlUrls[] = array_shift($newGameUrls);
            else
                break;
        }

        if(!empty($curlUrls)) {

            $curlData = $this->getAfterCurlData($curlUrls);

            foreach ($curlData as $url => $somePage){
                $elementParsing = phpQuery::newDocument($somePage);
                $gameID = substr($url, -12);

                $folder = 'images/game_img/' . $gameID . '/';
                $newFolder = 'images/new_img/' . $gameID . '/';
                mkdir($folder, 0777, true);
                mkdir($newFolder, 0777, true);

                //Превью игры
                $imgElement_gamePrewie = $elementParsing->find('.pi-product-image picture > img')->attr('src');
                if(substr($imgElement_gamePrewie, 0, 6) != 'https:') {
                    $imgElement_gamePrewie = 'https:' . $imgElement_gamePrewie;
                }
                file_put_contents($folder . $gameID . '-prewie.jpg', file_get_contents($imgElement_gamePrewie));
                file_put_contents($newFolder . $gameID . '-prewie.jpg', file_get_contents($imgElement_gamePrewie));

                // Скриншоты игры
                $screenArray = array();
                foreach ($elementParsing->find('.cli_screenshot_gallery li') as $listDataImg) {
                    $listDataImg = pq($listDataImg);
                    $screenArray[] = 'https:' . $listDataImg->find('img')->attr('data-srcset');
                }
                $sreenCount = 1;
                foreach ($screenArray as $screenUrl) {
                    if (!empty($screenUrl)) {
                        file_put_contents($folder . $gameID . '-scr-' . $sreenCount . '.jpg', file_get_contents($screenUrl));
                        file_put_contents($newFolder . $gameID . '-scr-' . $sreenCount . '.jpg', file_get_contents($screenUrl));
                        $sreenCount++;
                    }
                }

                // Тайтловое изображение игры
                $imgElement_title = $elementParsing->find('#backgroundImage picture img')->attr('src');
                if(empty($imgElement_title)) {
                    $imgElement_title = $elementParsing->find('.f-end-poster-image .f-post-image')->attr('src');
                }
                if(!empty($imgElement_title)) {
                    file_put_contents($folder . $gameID . '-title.jpg', file_get_contents($imgElement_title));
                    file_put_contents($newFolder . $gameID . '-title.jpg', file_get_contents($imgElement_title));
                }

                // Рекомендованные игры
                $recommend = '';
                foreach ($elementParsing->find('#relateditems .m-channel-placement .c-carousel div ul li') as $listData) {
                    $listData = pq($listData);
                    $recommend = $recommend . ' ' . $listData->attr('id');
                }
                $this->gamesArray[$gameID]['recommend'] = $recommend;
            }
        }

        if(!empty($newGameUrls))
            $this->getGameImg($gamePageElements, $newGameUrls, $iterations);
    }

    public function currencyPrice($countryID, array $currency) {

        if($countryID !== 'usa_en_us') {

            foreach ($this->gamesArray as $gameID => $gameData) {

                if ($this->gamesArray[$gameID]['game_price'][0] == '$')
                {
                    $this->gamesArray[$gameID]['game_price'] = str_replace('$', '', $this->gamesArray[$gameID]['game_price']);
                }
                if ( $this->gamesArray[$gameID]['before_discount'][0] == '$' )
                {
                    $this->gamesArray[$gameID]['before_discount'] = str_replace('$', '', $this->gamesArray[$gameID]['before_discount']);
                }

                if ( round($gameData['game_price'], 2) !== 0.0 ) {
                    $this->gamesArray[$gameID]['game_price'] = round(($gameData['game_price'] * $currency[$countryID]), 2);
                }
                if ( round($gameData['before_discount'], 2) !== 0.0) {
                    $this->gamesArray[$gameID]['before_discount'] = round(($gameData['before_discount'] * $currency[$countryID]), 2);
                }
            }
        }
    }

    public function getParsingData () {
        return $this->gamesArray;
    }

    public function getCountryTable ($countryTable) {
        $country = $this->getCountryName( $countryTable );
        return $country;
    }
}