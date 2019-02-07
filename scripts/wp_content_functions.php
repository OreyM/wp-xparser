<?php



function connectDataBase($host, $user, $password, $database) {
    return mysqli_connect($host, $user, $password, $database);
}

function getWPGamesArray ($queryResult) {

    $gamesArray = array();

    while ($queryData = $queryResult->fetch_object()) {
        $gamesArray[] = $queryData->meta_value;
    }
    return $gamesArray;
}


function getParserGamesArray($queryResult, $count = FALSE) {

    # Массив, куда пометим все игры, парсенные программой
    $gamesArray = array();
    # Переменная, для формирования определенного количества игр в массиве
    $loopCount = 0;

    # Парсим данные из базы данных
    while ( $queryData = $queryResult->fetch_object() ) {
        # Массив, для хранения скриншотов
        $scrArray = array();

        # Счетчик прохода по каждой строке БД
        $loopCount++;

        $titleImg = '';

        # Какие картинке к игре спарсенны, получаем именна файлов
        if ($gameImgDir = opendir('../images/game_img/' . $queryData->game_id)) {
            while (false !== ($imgFile = readdir($gameImgDir))) {
                if ($imgFile != '.' && $imgFile != '..') {

                    if ( strpos( $imgFile, '-prewie') ) {
                        $prewieImg = $imgFile;
                    } else if ( strpos( $imgFile, '-scr-') ) {
                        $scrArray[] = $imgFile;
                    } elseif ( strpos( $imgFile, '-title') ) {
                        $titleImg = $imgFile;
                    }
                }
            }
            closedir($gameImgDir);
        }

        # Формируем масив игр
        $gamesArray[$queryData->game_id] = [
            'game_name'         => html_entity_decode($queryData->game_name),
            'game_id'           => $queryData->game_id,
            'prewie_img'        => $prewieImg,
            'scr_img'           => $scrArray,
            'title_img'         => $titleImg,
            'country_price'     => $queryData->country_price,
            'before_discount'   => $queryData->before_discount,
            'country'           => $queryData->country,
            'game_link'         => $queryData->game_link,
            'free_game'         => $queryData->free_game,
            'discount'          => $queryData->discount,
            'discount_type'     => $queryData->discount_type,
            'next_prc_1'        => $queryData->next_prc_1,
            'next_ctr_1'        => $queryData->next_ctr_1,
            'next_link_1'       => $queryData->next_link_1,
            'next_prc_2'        => $queryData->next_prc_2,
            'next_ctr_2'        => $queryData->next_ctr_2,
            'next_link_2'       => $queryData->next_link_2,
            'next_prc_3'        => $queryData->next_prc_3,
            'next_ctr_3'        => $queryData->next_ctr_3,
            'next_link_3'       => $queryData->next_link_3,
            'next_prc_4'        => $queryData->next_prc_4,
            'next_ctr_4'        => $queryData->next_ctr_4,
            'next_link_4'       => $queryData->next_link_4
        ];
        # Прерываем работу функции, ели счетчик вышел на заданное значение
        if( $count && $loopCount === $count )
            break;
    }

    # Возвращаем массив игр
    return $gamesArray;
}

function insertGamePost ($dbConnect, $post_author, $post_title, $post_status, $post_name, $post_parent, $guid, $post_type, $post_mime_type) {

    //NEW GAME

//    $pc_posts = [
//
//        'ID' => NULL,
//        'post_author' => '1', //
//        'post_date' => '0000-00-00 00:00:00.000000',
//        'post_date_gmt' => '0000-00-00 00:00:00.000000',
//        'post_content' => '',
//        'post_title' => $gameName, //
//        'post_excerpt' => '',
//        'post_status' => 'draft', //
//        'comment_status' => 'closed',
//        'ping_status' => 'closed',
//        'post_password' => '',
//        'post_name' => '',
//        'to_ping' => '',
//        'pinged' => '',
//        'post_modified'	=> '0000-00-00 00:00:00.000000',
//        'post_modified_gmt'	=> '0000-00-00 00:00:00.000000',
//        'post_content_filtered' => '',
//        'post_parent' => '0', //
//        'guid' => '',
//        'menu_order' => '0',
//        'post_type' => 'games', //
//        'post_mime_type' => '', //
//        'comment_count' => '0'
//
//    ];

    //NEW IMG

//    $pc_posts = [
//
//        'ID' => NULL,
//        'post_author' => '1', //
//        'post_date' => '0000-00-00 00:00:00.000000',
//        'post_date_gmt' => '0000-00-00 00:00:00.000000',
//        'post_content' => '',
//        'post_title' => $gameName, //
//        'post_excerpt' => '',
//        'post_status' => 'draft', //
//        'comment_status' => 'closed',
//        'ping_status' => 'closed',
//        'post_password' => '',
//        'post_name' => $img,
//        'to_ping' => '',
//        'pinged' => '',
//        'post_modified'	=> '0000-00-00 00:00:00.000000',
//        'post_modified_gmt'	=> '0000-00-00 00:00:00.000000',
//        'post_content_filtered' => '',
//        'post_parent' => $postGameID, //
//        'guid' => '{$imgPath}{$gameID}/{$img}',
//        'menu_order' => '0',
//        'post_type' => 'attachment', //
//        'post_mime_type' => 'image/jpeg', //
//        'comment_count' => '0'
//
//    ];

    # Запрос для добавление данных в БД
    $postInsert = "
    INSERT INTO `pc_posts`
    (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)

    VALUES

    (NULL, '{$post_author}', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000', '', '{$post_title}', '', '{$post_status}', 'closed', 'closed', '', '{$post_name}', '', '', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000', '', '{$post_parent}', '{$guid}', '0', '{$post_type}', '{$post_mime_type}', '0');
    ";

    $result = mysqli_query($dbConnect, $postInsert) or die("Ошибка " . mysqli_error($result));
    return mysqli_insert_id($dbConnect);
}

function insertGameField ($dbConnect, $post_id, $meta_key, $meta_value, $id_query = FALSE) {

    # Запрос для добавление данных в таблицу полей
    $postFieldInsert = "
    INSERT INTO 
    `pc_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) 
    
    VALUES 
    
    (NULL, '{$post_id}', '{$meta_key}', '{$meta_value}');
    ";

    $result = mysqli_query($dbConnect, $postFieldInsert) or die("Ошибка " . mysqli_error($result));

    if ($id_query) {
        return mysqli_insert_id($dbConnect);
    }
}

function updateGameField ($dbConnect, $post_id, $meta_key, $meta_value) {

    # Запрос для обновления данных в таблице полей
    $postFieldUpdate = "
    UPDATE 
    `pc_postmeta` 
    SET 
    `meta_value` = '{$meta_value}' 
    WHERE 
    `pc_postmeta`.`post_id` = {$post_id} AND `pc_postmeta`.`meta_key` = '{$meta_key}';
    ";

    mysqli_query($dbConnect, $postFieldUpdate);
}