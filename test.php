# Добавляем новую игру в базу данных, получаем ID игры
$gamePost_ID = insertGamePost($wp_link, '1', $gameData['game_name'], 'draft', '', '0', '', 'games', '');
# Заносим ID игры в поле  ..  _game_id -> field_5bb1f88a34b8e
insertGameField($wp_link, $gamePost_ID, 'game_id', $gameID);
insertGameField($wp_link, $gamePost_ID, '_game_id', 'field_5bb1f88a34b8e');

# Добавляем превью изображения игры, получаем ID превью
# Проверяем, есть ли у нас это изображение вообще
//        if (!empty($gameData['prewie_img'])) {
//            $imgPath = $gamesImgPath . $gameID . '/' . $gameData['prewie_img'];
//            $imgPrewie_ID = insertGamePost($wp_link, '1', $gameData['prewie_img'], 'inherit', $gameData['prewie_img'], $gamePost_ID, $imgPath, 'attachment', 'image/jpeg');
//            # Активируем превью изображения игры в CMS  ..  _wp_attached_file -> games/game_id/game_id.jpg
//            # Получаем ID изображения в полях
//            $imgFieldPath = 'games/' . $gameID . '/' . $gameID . '-prewie.jpg';
//            $imgPrewie_fieldID = insertGameField($wp_link, $imgPrewie_ID, '_wp_attached_file', $imgFieldPath, TRUE);
//            # Заносим превью изображения игры в поле игры  ..  _img_prewie -> field_5bb1f89a34b8f
//            insertGameField($wp_link, $gamePost_ID, 'img_prewie', $imgPrewie_ID);
//            insertGameField($wp_link, $gamePost_ID, '_img_prewie', 'field_5bb1f89a34b8f');
//        }

# Добавляем тайтл изображение игры, получаем ID тайтла
# Проверяем, есть ли у нас это изображение вообще
//        if (!empty($gameData['title_img'])) {
//            $imgPath = $gamesImgPath . $gameID . '/' . $gameData['title_img'];
//            $imgTitle_ID = insertGamePost($wp_link, '1', $gameData['title_img'], 'inherit', $gameData['title_img'], $gamePost_ID, $imgPath, 'attachment', 'image/jpeg');
//            # Активируем тайтл изображения игры в CMS  ..  _wp_attached_file -> games/game_id/game_id.jpg
//            # Получаем ID изображения в полях
//            $imgFieldPath = 'games/' . $gameID . '/' . $gameID . '-title.jpg';
//            $imgTitle_fieldID = insertGameField($wp_link, $imgTitle_ID, '_wp_attached_file', $imgFieldPath, TRUE);
//            # Заносим тайтл изображения игры в поле игры  ..  _img_title -> field_5bb1f8da34b90
//            insertGameField($wp_link, $gamePost_ID, 'img_title', $imgTitle_ID);
//            insertGameField($wp_link, $gamePost_ID, '_img_title', 'field_5bb1f8da34b90');
//        }

# Добавляем скриншоты игры
# Проверяем есть ли они вообще
//        if (!empty($gameData['scr_img'])) {
//            # Переменная для формирования параметров хранения скриншотов
//            # Параметры хранения скриншотов  ..  a:4:{i:0;s:3:"132";i:1;s:3:"133";i:2;s:3:"134";i:3;s:3:"135";}
//            # a:4 - размер масива криншотов
//            $scrImgParam = 'a:' . count($gameData['scr_img']) . ':{';
//
//            # Переменная счетчика количества криншотов
//            $scrCount = 0;
//
//            # Перебераем маcив скриншотов
//            foreach ($gameData['scr_img'] as $scrImg) {
//                # Добавляем скриншот игры, получаем ID скриншота
//                $imgPath = $gamesImgPath . $gameID . '/' . $scrImg;
//                $imgTitle_ID = insertGamePost($wp_link, '1', $scrImg, 'inherit', $scrImg, $gamePost_ID, $imgPath, 'attachment', 'image/jpeg');
//                # Активируем скриншот изображения игры в CMS  ..  _wp_attached_file -> games/game_id/game_id.jpg
//                $imgFieldPath = 'games/' . $gameID . '/' . $scrImg;
//                insertGameField($wp_link, $imgTitle_ID, '_wp_attached_file', $imgFieldPath);
//                # Дополняем параметры хранения скриншотов
//                $scrImgParam .= 'i:' . $scrCount . ';s:3:"' . $imgTitle_ID . '";';
//                # Увеличиваем счетчик количества криншотов
//                $scrCount++;
//            }
//
//            # Завершаем формирование параметров хранения скринштов и добавляем в БД
//            # _img_screenshots -> field_5bb1f8ec34b91
//            $scrImgParam .= '}';
//            insertGameField($wp_link, $gamePost_ID, 'img_screenshots', $scrImgParam);
//            insertGameField($wp_link, $gamePost_ID, '_img_screenshots', 'field_5bb1f8ec34b91');
//        }

# РАБОТА C ЦЕНАМИ И СТРАНАМИ
# Лучшая цена  ..  _game_bestPrice -> field_5bb491c5b42bc
insertGameField($wp_link, $gamePost_ID, 'game_bestPrice', $gameData['country_price']);
insertGameField($wp_link, $gamePost_ID, '_game_bestPrice', 'field_5bb491c5b42bc');
# Цена до скидки  ..  _game_bfPrice -> field_5bb492fbb42bd
insertGameField($wp_link, $gamePost_ID, 'game_bfPrice', $gameData['before_discount']);
insertGameField($wp_link, $gamePost_ID, '_game_bfPrice', 'field_5bb492fbb42bd');
# Страна с лучшей ценой  ..  _game_bestContr -> field_5bb49331b42be
insertGameField($wp_link, $gamePost_ID, 'game_bestContr', $gameData['country']);
insertGameField($wp_link, $gamePost_ID, '_game_bestContr', 'field_5bb49331b42be');
# Ссылка на игру с лучшей ценной  ..  _game_link -> field_5bb49370b0334
insertGameField($wp_link, $gamePost_ID, 'game_link', $gameData['game_link']);
insertGameField($wp_link, $gamePost_ID, '_game_link', 'field_5bb49370b0334');
#### Цены первой страны
# _game_nextPrc_1 -> field_5bb60d222f68c
insertGameField($wp_link, $gamePost_ID, 'game_nextPrc_1', $gameData['next_prc_1']);
insertGameField($wp_link, $gamePost_ID, '_game_nextPrc_1', 'field_5bb60d222f68c');
# _game_nextCntr_1 -> field_5bb60d6a2f690
insertGameField($wp_link, $gamePost_ID, 'game_nextCntr_1', $gameData['next_ctr_1']);
insertGameField($wp_link, $gamePost_ID, '_game_nextCntr_1', 'field_5bb60d6a2f690');
# _game_nextLink_1 -> field_5bb60da42f694
insertGameField($wp_link, $gamePost_ID, 'game_nextLink_1', $gameData['next_link_1']);
insertGameField($wp_link, $gamePost_ID, '_game_nextLink_1', 'field_5bb60da42f694');
#### Цены второй страны
# _game_nextPrc_2 -> field_5bb60d522f68d
insertGameField($wp_link, $gamePost_ID, 'game_nextPrc_2', $gameData['next_prc_2']);
insertGameField($wp_link, $gamePost_ID, '_game_nextPrc_2', 'field_5bb60d522f68d');
# _game_nextCntr_2 -> field_5bb60d852f691
insertGameField($wp_link, $gamePost_ID, 'game_nextCntr_2', $gameData['next_ctr_2']);
insertGameField($wp_link, $gamePost_ID, '_game_nextCntr_2', 'field_5bb60d852f691');
# _game_nextLink_2 -> field_5bb60dc22f695
insertGameField($wp_link, $gamePost_ID, 'game_nextLink_2', $gameData['next_link_2']);
insertGameField($wp_link, $gamePost_ID, '_game_nextLink_2', 'field_5bb60dc22f695');
#### Цены третьей страны
# _game_nextPrc_3 -> field_5bb60d5b2f68e
insertGameField($wp_link, $gamePost_ID, 'game_nextPrc_3', $gameData['next_prc_3']);
insertGameField($wp_link, $gamePost_ID, '_game_nextPrc_3', 'field_5bb60d5b2f68e');
# _game_nextCntr_3 -> field_5bb60d8e2f692
insertGameField($wp_link, $gamePost_ID, 'game_nextCntr_3', $gameData['next_ctr_3']);
insertGameField($wp_link, $gamePost_ID, '_game_nextCntr_3', 'field_5bb60d8e2f692');
# _game_nextLink_3 -> field_5bb60dcf2f696
insertGameField($wp_link, $gamePost_ID, 'game_nextLink_3', $gameData['next_link_3']);
insertGameField($wp_link, $gamePost_ID, '_game_nextLink_3', 'field_5bb60dcf2f696');
#### Цены четвертой страны
# _game_nextPrc_4 -> field_5bb60d632f68f
insertGameField($wp_link, $gamePost_ID, 'game_nextPrc_4', $gameData['next_prc_4']);
insertGameField($wp_link, $gamePost_ID, '_game_nextPrc_4', 'field_5bb60d632f68f');
# _game_nextCntr_4 -> field_5bb60d992f693
insertGameField($wp_link, $gamePost_ID, 'game_nextCntr_4', $gameData['next_ctr_4']);
insertGameField($wp_link, $gamePost_ID, '_game_nextCntr_4', 'field_5bb60d992f693');
# _game_nextLink_4 -> field_5bb60dd92f697
insertGameField($wp_link, $gamePost_ID, 'game_nextLink_4', $gameData['next_link_4']);
insertGameField($wp_link, $gamePost_ID, '_game_nextLink_4', 'field_5bb60dd92f697');

# Информация о беплатности и скидках
# Бесплатная игра  ..  _game_freeGame -> field_5bb600afa3bf7
insertGameField($wp_link, $gamePost_ID, 'game_freeGame', $gameData['free_game']);
insertGameField($wp_link, $gamePost_ID, '_game_freeGame', 'field_5bb600afa3bf7');
# Наличие скидки  ..  _game_discount -> field_5bb600e8a3bf8
insertGameField($wp_link, $gamePost_ID, 'game_discount', $gameData['discount']);
insertGameField($wp_link, $gamePost_ID, '_game_discount', 'field_5bb600e8a3bf8');
# Тип скидки  ..  _game_discountType -> field_5bb60108a3bf9
insertGameField($wp_link, $gamePost_ID, 'game_discountType', $gameData['discount_type']);
insertGameField($wp_link, $gamePost_ID, '_game_discountType', 'field_5bb60108a3bf9');

# Добавляем актуальность игры  ..  _game_active -> field_5bb4b9da901ec
insertGameField($wp_link, $gamePost_ID, 'game_active', '1');
insertGameField($wp_link, $gamePost_ID, '_game_active', 'field_5bb4b9da901ec');





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