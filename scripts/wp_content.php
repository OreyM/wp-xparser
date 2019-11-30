<?php
require_once '../config/config.php';
require_once '../class/debug/Debug.php';
require_once 'wp_content_functions.php';

new Debug;

$startInsertTime = microtime(true);


// WP DataBase connect
$wp_link = mysqli_connect(WP_HOST, WP_USERNAME, WP_PASSWORD, WP_DATABASE);
# Получаем все игры по ID, что уже хранятся на сайте
$resultWP = mysqli_query($wp_link, "Select meta_value FROM pc_postmeta WHERE meta_key = 'game_id'");

// Parser DataBase connect
$parser_link = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
# Получаем масив всех игр, которые были спарсены
$resultParser = mysqli_query($parser_link, "Select * FROM games");


# Массив игр сайта
$wpGamesArray = getWPGamesArray($resultWP);
# Массив игр  парсера, ВТОРОЙ ПАРАМЕТР - сколько игр обработать
$parserGamesArray = getParserGamesArray($resultParser, FALSE);

# Путь хранения изображений для игр
$gamesImgPath = 'https://playone.club/images/games/';

$queryInsert = '';
$queryUpdate = '';

# Перебераем полученный массив игр
foreach ( $parserGamesArray as $gameID => $gameData ) {

    # Проверяем, нет ли этой игры уже в базе
    if ( !in_array( $gameID, $wpGamesArray) ) {

# Добавляем новую игру в базу данных, получаем ID игры
        $gamePost_ID = insertGamePost($wp_link, '1', html_entity_decode($gameData['game_name']), 'draft', '', '0', '', 'games', '');

# Подготавливаем мультизапрос для вставки в таблицу
        $queryInsert .= '';

# Заносим ID игры в поле  ..  _game_id -> field_5bb1f88a34b8e
        $queryInsert .= "
                INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_id', '{$gameID}');
                INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_id', 'field_5bb1f88a34b8e');
                ";

# Добавляем превью изображения игры, получаем ID превью
# Проверяем, есть ли у нас это изображение вообще
        if (!empty($gameData['prewie_img'])) {
            $imgPath = $gamesImgPath . $gameID . '/' . $gameData['prewie_img'];
            $imgPrewie_ID = insertGamePost($wp_link, '1', $gameData['prewie_img'], 'inherit', $gameData['prewie_img'], $gamePost_ID, $imgPath, 'attachment', 'image/jpeg');
# Активируем превью изображения игры в CMS  ..  _wp_attached_file -> games/game_id/game_id.jpg
# Получаем ID изображения в полях
            $imgFieldPath = 'games/' . $gameID . '/' . $gameID . '-prewie.jpg';
            $imgPrewie_fieldID = insertGameField($wp_link, $imgPrewie_ID, '_wp_attached_file', $imgFieldPath, TRUE);
# Заносим превью изображения игры в поле игры  ..  _img_prewie -> field_5bb1f89a34b8f
            $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'img_prewie', '{$imgPrewie_ID}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_img_prewie', 'field_5bb1f89a34b8f');
";
        }

# Добавляем тайтл изображение игры, получаем ID тайтла
# Проверяем, есть ли у нас это изображение вообще
        if (!empty($gameData['title_img'])) {
            $imgPath = $gamesImgPath . $gameID . '/' . $gameData['title_img'];
            $imgTitle_ID = insertGamePost($wp_link, '1', $gameData['title_img'], 'inherit', $gameData['title_img'], $gamePost_ID, $imgPath, 'attachment', 'image/jpeg');
# Активируем тайтл изображения игры в CMS  ..  _wp_attached_file -> games/game_id/game_id.jpg
# Получаем ID изображения в полях
            $imgFieldPath = 'games/' . $gameID . '/' . $gameID . '-title.jpg';
            $imgTitle_fieldID = insertGameField($wp_link, $imgTitle_ID, '_wp_attached_file', $imgFieldPath, TRUE);
# Заносим тайтл изображения игры в поле игры  ..  _img_title -> field_5bb1f8da34b90
            $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'img_title', '{$imgTitle_ID}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_img_title', 'field_5bb1f8da34b90');
";
        }

# Добавляем скриншоты игры
# Проверяем есть ли они вообще
        if (!empty($gameData['scr_img'])) {
# Переменная для формирования параметров хранения скриншотов
# Параметры хранения скриншотов  ..  a:4:{i:0;s:3:"132";i:1;s:3:"133";i:2;s:3:"134";i:3;s:3:"135";}
# a:4 - размер масива криншотов
            $scrImgParam = 'a:' . count($gameData['scr_img']) . ':{';

# Переменная счетчика количества криншотов
            $scrCount = 0;

# Перебераем маcив скриншотов
            foreach ($gameData['scr_img'] as $scrImg) {
# Добавляем скриншот игры, получаем ID скриншота
                $imgPath = $gamesImgPath . $gameID . '/' . $scrImg;
                $imgTitle_ID = insertGamePost($wp_link, '1', $scrImg, 'inherit', $scrImg, $gamePost_ID, $imgPath, 'attachment', 'image/jpeg');
# Активируем скриншот изображения игры в CMS  ..  _wp_attached_file -> games/game_id/game_id.jpg
                $imgFieldPath = 'games/' . $gameID . '/' . $scrImg;
                insertGameField($wp_link, $imgTitle_ID, '_wp_attached_file', $imgFieldPath);
# Дополняем параметры хранения скриншотов
                $scrImgParam .= 'i:' . $scrCount . ';s:3:"' . $imgTitle_ID . '";';
# Увеличиваем счетчик количества криншотов
                $scrCount++;
            }

# Завершаем формирование параметров хранения скринштов и добавляем в БД
# _img_screenshots -> field_5bb1f8ec34b91
            $scrImgParam .= '}';
            $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'img_screenshots', '{$scrImgParam}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_img_screenshots', 'field_5bb1f8ec34b91');
";
        }

# РАБОТА C ЦЕНАМИ И СТРАНАМИ
# Лучшая цена  ..  _game_bestPrice -> field_5bb491c5b42bc
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_bestPrice', '{$gameData['country_price']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_bestPrice', 'field_5bb491c5b42bc');
";
# Цена до скидки  ..  _game_bfPrice -> field_5bb492fbb42bd
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_bfPrice', '{$gameData['before_discount']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_bfPrice', 'field_5bb492fbb42bd');
";
# Страна с лучшей ценой  ..  _game_bestContr -> field_5bb49331b42be
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_bestContr', '{$gameData['country']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_bestContr', 'field_5bb49331b42be');
";
# Ссылка на игру с лучшей ценной  ..  _game_link -> field_5bb49370b0334
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_link', '{$gameData['game_link']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_link', 'field_5bb49370b0334');
";
#### Цены первой страны
# _game_nextPrc_1 -> field_5bb60d222f68c
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextPrc_1', '{$gameData['next_prc_1']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextPrc_1', 'field_5bb60d222f68c');
";
# _game_nextCntr_1 -> field_5bb60d6a2f690
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextCntr_1', '{$gameData['next_ctr_1']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextCntr_1', 'field_5bb60d6a2f690');
";
# _game_nextLink_1 -> field_5bb60da42f694
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextLink_1', '{$gameData['next_link_1']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextLink_1', 'field_5bb60da42f694');
";
#### Цены второй страны
# _game_nextPrc_2 -> field_5bb60d522f68d
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextPrc_2', '{$gameData['next_prc_2']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextPrc_2', 'field_5bb60d522f68d');
";
# _game_nextCntr_2 -> field_5bb60d852f691
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextCntr_2', '{$gameData['next_ctr_2']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextCntr_2', 'field_5bb60d852f691');
";
# _game_nextLink_2 -> field_5bb60dc22f695
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextLink_2', '{$gameData['next_link_2']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextLink_2', 'field_5bb60dc22f695');
";
#### Цены третьей страны
# _game_nextPrc_3 -> field_5bb60d5b2f68e
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextPrc_3', '{$gameData['next_prc_3']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextPrc_3', 'field_5bb60d5b2f68e');
";
# _game_nextCntr_3 -> field_5bb60d8e2f692
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextCntr_3', '{$gameData['next_ctr_3']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextCntr_3', 'field_5bb60d8e2f692');
";
# _game_nextLink_3 -> field_5bb60dcf2f696
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextLink_3', '{$gameData['next_link_3']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextLink_3', 'field_5bb60dcf2f696');
";
#### Цены четвертой страны
# _game_nextPrc_4 -> field_5bb60d632f68f
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextPrc_4', '{$gameData['next_prc_4']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextPrc_4', 'field_5bb60d632f68f');
";
# _game_nextCntr_4 -> field_5bb60d992f693
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextCntr_4', '{$gameData['next_ctr_4']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextCntr_4', 'field_5bb60d992f693');
";
# _game_nextLink_4 -> field_5bb60dd92f697
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_nextLink_4', '{$gameData['next_link_4']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_nextLink_4', 'field_5bb60dd92f697');
";

# Информация о беплатности и скидках
# Бесплатная игра  ..  _game_freeGame -> field_5bb600afa3bf7
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_freeGame', '{$gameData['free_game']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_freeGame', 'field_5bb600afa3bf7');
";
# Наличие скидки  ..  _game_discount -> field_5bb600e8a3bf8
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_discount', '{$gameData['discount']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_discount', 'field_5bb600e8a3bf8');
";
# Тип скидки  ..  _game_discountType -> field_5bb60108a3bf9
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_discountType', '{$gameData['discount_type']}');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_discountType', 'field_5bb60108a3bf9');
";

# Добавляем актуальность игры  ..  _game_active -> field_5bb4b9da901ec
        $queryInsert .= "
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', 'game_active', '1');
INSERT INTO pc_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (NULL, '{$gamePost_ID}', '_game_active', 'field_5bb4b9da901ec');
";

    }
    # Если игра уже есть в базе данных, обновляем ценны
    else {

        # Получаем ID игры в таблице полей
        $resultCheck = mysqli_query($wp_link, "Select post_id FROM pc_postmeta WHERE meta_value = '{$gameID}' AND meta_key = 'game_id'")->fetch_object();
        $postID = $resultCheck->post_id;

        /** Данные таблицы
        # Обновляем лучшую цену  ..  _game_bestPrice -> field_5bb491c5b42bc
        # Обновляем Цена до скидки  ..  _game_bfPrice -> field_5bb492fbb42bd
        # Обновляем Страна с лучшей ценой  ..  _game_bestContr -> field_5bb49331b42be
        # Обновляем Ссылка на игру с лучшей ценной  ..  _game_link -> field_5bb49370b0334
        #### Цены первой страны
        # _game_nextPrc_1 -> field_5bb60d222f68c
        # _game_nextCntr_1 -> field_5bb60d6a2f690
        # _game_nextLink_1 -> field_5bb60da42f694
        #### Цены второй страны
        # _game_nextPrc_2 -> field_5bb60d522f68d
        # _game_nextCntr_2 -> field_5bb60d852f691
        # _game_nextLink_2 -> field_5bb60dc22f695
        #### Цены третьей страны
        # _game_nextPrc_3 -> field_5bb60d5b2f68e
        # _game_nextCntr_3 -> field_5bb60d8e2f692
        # _game_nextLink_3 -> field_5bb60dcf2f696
        #### Цены четвертой страны
        # _game_nextPrc_4 -> field_5bb60d632f68f
        # _game_nextCntr_4 -> field_5bb60d992f693
        # _game_nextLink_4 -> field_5bb60dd92f697
        # Информация о беплатности и скидках
        # Бесплатная игра  ..  _game_freeGame -> field_5bb600afa3bf7
        # Наличие скидки  ..  _game_discount -> field_5bb600e8a3bf8
        # Тип скидки  ..  _game_discountType -> field_5bb60108a3bf9
         */

        if ( TRUE ) // for debug
        {
            $queryUpdate .= "
        UPDATE pc_postmeta SET meta_value = '{$gameData['country_price']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_bestPrice';
        UPDATE pc_postmeta SET meta_value = '{$gameData['before_discount']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_bfPrice';
        UPDATE pc_postmeta SET meta_value = '{$gameData['country']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_bestContr';
        UPDATE pc_postmeta SET meta_value = '{$gameData['game_link']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_link';

        UPDATE pc_postmeta SET meta_value = '{$gameData['free_game']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_freeGame';
        UPDATE pc_postmeta SET meta_value = '{$gameData['discount']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_discount';
        UPDATE pc_postmeta SET meta_value = '{$gameData['discount_type']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_discountType';

        UPDATE pc_postmeta SET meta_value = '{$gameData['next_prc_1']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextPrc_1';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_ctr_1']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextCntr_1';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_link_1']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextLink_1';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_prc_2']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextPrc_2';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_ctr_2']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextCntr_2';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_link_2']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextLink_2';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_prc_3']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextPrc_3';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_ctr_3']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextCntr_3';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_link_3']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextLink_3';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_prc_4']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextPrc_4';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_ctr_4']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextCntr_4';
        UPDATE pc_postmeta SET meta_value = '{$gameData['next_link_4']}' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_nextLink_4';
        UPDATE pc_postmeta SET meta_value = '1' WHERE pc_postmeta.post_id = '{$postID}' AND pc_postmeta.meta_key = 'game_active';
        ";
        }
    }
}
$getData = $queryUpdate . $queryInsert;
Debug::debug(mysqli_multi_query($wp_link, $getData));

/*
mysqli_multi_query($wp_link, $queryInsert);
mysqli_multi_query($wp_link, $queryUpdate);
*/


mysqli_close($wp_link);
mysqli_close($parser_link);


// Parser DataBase connect
$parser_link = connectDataBase(HOST, USERNAME, PASSWORD, DATABASE);
// WP DataBase connect
$wp_link = connectDataBase(WP_HOST, WP_USERNAME, WP_PASSWORD, WP_DATABASE);


# Проверяем актуальность игр
# Массив игр сайта
$resultWP = mysqli_query($wp_link, "Select meta_value FROM pc_postmeta WHERE meta_key = 'game_id'");
$wpGamesArray = getWPGamesArray($resultWP);

# Получаем масив всех игр, которые были спарсены
$resultParser = mysqli_query($parser_link, "Select game_id FROM games");
# Массив игр парсера
while ( $parserGameID = $resultParser->fetch_object() )
{
    $parserGamesArray[] = $parserGameID->game_id;
}

foreach ( $wpGamesArray as $wpGameID )
{
    if ( !in_array( $wpGameID, $parserGamesArray, FALSE) )
    {
        $resultCheck = mysqli_query($wp_link, "Select post_id FROM pc_postmeta WHERE meta_value = '{$wpGameID}'")->fetch_object();
        # Убираем актуальность игры  ..  _game_active -> field_5bb4b9da901ec
        if ( $resultCheck->post_id != 26231 )
        {
            mysqli_query($wp_link, "UPDATE pc_posts SET post_status = 'private' WHERE pc_posts.ID = '{$resultCheck->post_id}';");
            updateGameField($wp_link, $resultCheck->post_id, 'game_active', '0');
        }
    }
}

mysqli_close($wp_link);
mysqli_close($parser_link);


// Полное время обновления БД сайта
printf('Скрипт выполнялся %.4F сек.', (microtime(true) - $startInsertTime));