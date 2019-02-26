<?php

class TableCreate {

    protected   $gamesArray,
                $gamePriceArray;

    public function __construct() {
        $this->gamesArray = array();
        $this->gamePriceArray = array();
    }

    protected function countryConvert ($country) {

        switch ($country) {
            case 'usa_en_us':
                return 'США';
            case 'rus_ru_ru':
                return 'Россия';
            case 'argentina_es_ar':
                return 'Аргентина';
            case 'turkish_tr_tr':
                return 'Турция';
            case 'brazil_pt_br':
                return 'Бразилия';
            case 'columbia_es_co':
                return 'Колумбия';
            case 'india_en_in':
                return 'Индия';
            case 'africa_en_za':
                return 'Африка';
            case 'mexico_es_mx':
                return 'Мексика';
            case 'newzeland_en_nz':
                return 'Зеландия';
            case 'canada_en_ca':
                return 'Канада';
            default:
                return 'NO COUNTRY';
        }
    }

    public function toTranslit () {
        $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M', '%');
        $lat = array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya', 'q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m', '-procent');

        foreach ($this->gamesArray as $gameID => $arrayData) {
            $str = $arrayData['game_name'];

            $newStr = html_entity_decode($str);

            $newStr = str_replace('&apos;', '', $newStr);
            $newStr = str_replace('039;', '', $newStr);
            $newStr = str_replace(" ", "-", (preg_replace("/  +/", ' ', (preg_replace("/[^0-9a-z-_ ]/i", "", (str_replace($rus, $lat, (trim(preg_replace("/  +/", ' ', $newStr))))))))));
            $newStr= preg_replace("/--+/", '-', $newStr); // удаляем повторяющие тире

            $this->gamesArray[$gameID]['site_link'] = $newStr;
        }
    }

    public function firstGamesData ($countryArray) {

        $connect = Database::checkConnect();
        $db = $connect->connectDatabase();

        foreach ( $countryArray as $tableName => $gameID ) {
            $result = $connect->selectData($db, $tableName, 'game_id, game_name');

            while ( $queryData = $result->fetch_object() ) {

                if( !in_array($queryData->game_id, $this->gamesArray, true) ) {

                    if ( $tableName !== 'usa_en_us' && empty($this->gamesArray[$queryData->game_id]['game_name']) ) {
                        if ( $name = $connect->selectWhere($db, 'canada_en_ca', 'game_name', 'game_id', $queryData->game_id, TRUE) ) {
                            $gameName = $name->game_name;
                        } elseif ( $name = $connect->selectWhere($db, 'newzeland_en_nz', 'game_name', 'game_id', $queryData->game_id, TRUE) ) {
                            $gameName = $name->game_name;
                        } elseif ( $name = $connect->selectWhere($db, 'africa_en_za', 'game_name', 'game_id', $queryData->game_id, TRUE) ) {
                            $gameName = $name->game_name;
                        } elseif ( $name = $connect->selectWhere($db, 'india_en_in', 'game_name', 'game_id', $queryData->game_id, TRUE) ) {
                            $gameName = $name->game_name;
                        } elseif ( $name = $connect->selectWhere($db, 'rus_ru_ru', 'game_name', 'game_id', $queryData->game_id, TRUE) ) {
                            $gameName = $name->game_name;
                        }
                        else {
                            $queryData->game_name;
                        }
                    } else {
                        $gameName = $queryData->game_name;
                    }

                    $this->gamesArray[$queryData->game_id] = [
                        'game_id'           => $queryData->game_id, // ID игры (Type: String)
                        'game_name'         => $gameName,           // Название игры (Type: String)
                        'site_link'         => '',                  // Преобразованное название игры для ссылки на сайте (Type: String)
                        'country'           => '',                  // Страна с лучшей скидкой на игру (Type: String)
                        'country_price'     => '',                  // Лучшая цена на игру (Type: Float)
                        'before_discount'   => '',                  // Цена до скидки на игру (Type: Float)
                        'discount'          => '',                  // Есть ли скидка на игру (Type: Boolean)
                        'discount_type'     => '',                  // Тип скидки на игру (Type: String)
                        'free_game'         => '',                  // Бесплатна ли игра (Type: Boolean)
                        'game_link'         => '',                  // Ссылка на игру с лучшей ценой (Type: String)
                        // Другие цены на игры по странам, согласно сортировки
                        'next_ctr_1'        => '',                  // Тип скидки на игру (Type: String)
                        'next_prc_1'        => '',                  // Цена до скидки на игру (Type: Float)
                        'next_link_1'       => '',                  // Ссылка на игру (Type: String)
                        'next_ctr_2'        => '',                  // Тип скидки на игру (Type: String)
                        'next_prc_2'        => '',                  // Цена до скидки на игру (Type: Float)
                        'next_link_2'       => '',                  // Ссылка на игру (Type: String)
                        'next_ctr_3'        => '',                  // Тип скидки на игру (Type: String)
                        'next_prc_3'        => '',                  // Цена до скидки на игру (Type: Float)
                        'next_link_3'       => '',                  // Ссылка на игру (Type: String)
                        'next_ctr_4'        => '',                  // Тип скидки на игру (Type: String)
                        'next_prc_4'        => '',                  // Цена до скидки на игру (Type: Float)
                        'next_link_4'       => ''                   // Ссылка на игру (Type: String)
                    ];
                }
            }
        }
        $db->close();
    }

    public function getGamesArray () {
        return $this->gamesArray;
    }

    public function checkGame () {

        $connect = Database::checkConnect();
        $db = $connect->connectDatabase();

        foreach ($this->gamesArray as $gameData) {

            $result = $connect->selectWhere($db, GAME_TABLE, 'game_id', 'game_id', $gameData['game_id'], TRUE);

            if (!$result) {
                $gameID = $gameData['game_id'];
                $connect->insertData($db, GAME_TABLE, $this->gamesArray[$gameID]);
            }
        }
        $db->close();
    }

    public function sortBestPrice ($countryArray) {

        $connect = Database::checkConnect();
        $db = $connect->connectDatabase();

        foreach ( $this->gamesArray as $gameID => $games ) {

            foreach ($countryArray as $tableName => $countryID) {
                $price = $connect->selectWhere($db, $tableName, 'game_price, before_discount, free_game', 'game_id', $gameID, TRUE);

                if ($price) {

                    if ($price->before_discount > 0 && (bool)$price->free_game) {
                        $this->gamePriceArray[$gameID][$tableName] = $price->before_discount;
                    } else {
                        $this->gamePriceArray[$gameID][$tableName] = $price->game_price;
                    }
                }
            }
            asort($this->gamePriceArray[$gameID]);
        }

        $db->close();
    }

    public function getGamesPriceArray () {
        return $this->gamePriceArray;
    }

    public function addPriceToGamesArray (array $priceArray) {

        foreach ($priceArray as $gameID => $gamePrice) {
            $country = key($gamePrice);
            $this->gamesArray[$gameID]['country'] = $country;
            $this->gamesArray[$gameID]['country_price'] = (float)$gamePrice[$country];
            next($gamePrice);

            $country = key($gamePrice);
            if ( NULL !== $country ) {
                $this->gamesArray[$gameID]['next_ctr_1'] = $country;
                $this->gamesArray[$gameID]['next_prc_1'] = (float)$gamePrice[$country];
                next($gamePrice);

                $country = key($gamePrice);
                if ( NULL !== $country )
                {
                    $this->gamesArray[$gameID]['next_ctr_2'] = $country;
                    $this->gamesArray[$gameID]['next_prc_2'] = (float)$gamePrice[$country];
                    next($gamePrice);

                    $country = key($gamePrice);
                    if ( NULL !== $country )
                    {
                        $this->gamesArray[$gameID]['next_ctr_3'] = $country;
                        $this->gamesArray[$gameID]['next_prc_3'] = (float)$gamePrice[$country];
                        next($gamePrice);

                        $country = key($gamePrice);

                        if ( NULL !== $country )
                        {
                            $this->gamesArray[$gameID]['next_ctr_4'] = $country;
                            $this->gamesArray[$gameID]['next_prc_4'] = (float)$gamePrice[$country];
                            next($gamePrice);
                        }
                        else
                        {
                            $this->gamesArray[$gameID]['next_ctr_4'] = 'NO COUNTRY';
                            $this->gamesArray[$gameID]['next_prc_4'] = '0';
                        }
                    }
                    else
                    {
                        $this->gamesArray[$gameID]['next_ctr_3'] = 'NO COUNTRY';
                        $this->gamesArray[$gameID]['next_prc_3'] = '0';
                    }
                }
                else
                {
                    $this->gamesArray[$gameID]['next_ctr_2'] = 'NO COUNTRY';
                    $this->gamesArray[$gameID]['next_prc_2'] = '0';
                }
            }
            else
            {
                $this->gamesArray[$gameID]['next_ctr_1'] = 'NO COUNTRY';
                $this->gamesArray[$gameID]['next_prc_1'] = '0';
            }
        }
    }

    public function getAnotherGameData () {

        $connect = Database::checkConnect();
        $db = $connect->connectDatabase();



        foreach ($this->gamesArray as $gameID => $gameData) {
            $countryBestPrice = $gameData['country'];

            $result = $connect->selectWhere($db, $countryBestPrice, 'game_link, before_discount, discount, discount_type, free_game', 'game_id', $gameID, TRUE);

            $this->gamesArray[$gameID]['game_link'] = $result->game_link;
            $this->gamesArray[$gameID]['before_discount'] = (float)$result->before_discount;

            if ( $this->gamesArray[$gameID]['country_price'] === $this->gamesArray[$gameID]['before_discount']) {
                $this->gamesArray[$gameID]['country_price'] = 0.0;
            }

            $this->gamesArray[$gameID]['discount'] = (bool)$result->discount;
            $this->gamesArray[$gameID]['discount_type'] = $result->discount_type;
            $this->gamesArray[$gameID]['free_game'] = (bool)$result->free_game;

            if ( !empty( $this->gamesArray[$gameID]['next_prc_1'] ) ) {
                $newResult = $connect->selectWhere($db, $this->gamesArray[$gameID]['next_ctr_1'], 'game_link', 'game_id', $gameID, TRUE);
                $this->gamesArray[$gameID]['next_link_1'] = $newResult->game_link;
            }
            if ( !empty( $this->gamesArray[$gameID]['next_prc_2'] ) ) {
                $newResult = $connect->selectWhere($db, $this->gamesArray[$gameID]['next_ctr_2'], 'game_link', 'game_id', $gameID, TRUE);
                $this->gamesArray[$gameID]['next_link_2'] = $newResult->game_link;
            }
            if ( !empty( $this->gamesArray[$gameID]['next_prc_3'] ) ) {
                $newResult = $connect->selectWhere($db, $this->gamesArray[$gameID]['next_ctr_3'], 'game_link', 'game_id', $gameID, TRUE);
                $this->gamesArray[$gameID]['next_link_3'] = $newResult->game_link;
            }
            if ( !empty( $this->gamesArray[$gameID]['next_prc_4'] ) ) {
                $newResult = $connect->selectWhere($db, $this->gamesArray[$gameID]['next_ctr_4'], 'game_link', 'game_id', $gameID, TRUE);
                $this->gamesArray[$gameID]['next_link_4'] = $newResult->game_link;
            }
        }

        $db->close();
    }

    public function addCountryConvert () {

        foreach ($this->gamesArray as $gameID => $gameData) {
            $this->gamesArray[$gameID]['country'] = $this->countryConvert($gameData['country']);
            $this->gamesArray[$gameID]['next_ctr_1'] = $this->countryConvert($gameData['next_ctr_1']);
            $this->gamesArray[$gameID]['next_ctr_2'] = $this->countryConvert($gameData['next_ctr_2']);
            $this->gamesArray[$gameID]['next_ctr_3'] = $this->countryConvert($gameData['next_ctr_3']);
            $this->gamesArray[$gameID]['next_ctr_4'] = $this->countryConvert($gameData['next_ctr_4']);
        }
    }

    public function addGamesDB () {
        $connect = Database::checkConnect();
        $db = $connect->connectDatabase();

        foreach ( $this->gamesArray as $gameID => $gameData ) {
            $connect->updateData( $db, GAME_TABLE, $gameID, $gameData );
        }

        $db->close();
    }
}