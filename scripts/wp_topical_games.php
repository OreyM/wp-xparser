<?php
require_once '../config/config.php';
require_once 'wp_content_functions.php';

$startInsertTime = microtime(true);

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
# Массив игр  парсера, ВТОРОЙ ПАРАМЕТР - сколько игр обработать
//$parserGamesArray = getParserGamesArray($resultParser, FALSE);
while ( $parserGameID = $resultParser->fetch_object() )
{
    $parserGamesArray[] = $parserGameID->game_id;
}

//Debug::debug($parserGamesArray, $wpGamesArray);

foreach ( $wpGamesArray as $wpGameID )
{
    if ( !in_array( $wpGameID, $parserGamesArray, FALSE) )
    {
        $resultCheck = mysqli_query($wp_link, "Select post_id FROM pc_postmeta WHERE meta_value = '{$wpGameID}'")->fetch_object();
        # Убираем актуальность игры  ..  _game_active -> field_5bb4b9da901ec
        mysqli_query($wp_link, "UPDATE pc_posts SET post_status = 'private' WHERE pc_posts.ID = '{$resultCheck->post_id}';");
        updateGameField($wp_link, $resultCheck->post_id, 'game_active', '0');
        //updateGameField($wp_link, $resultCheck->post_id, '_game_active', 'field_5bb4b9da901ec');
    }
    else
    {
        #Поиск в отброшенных ранее играх, которые при предыдущем парсинге выявлено не было
        # Получаем ID поста игры
        $resultCheck = mysqli_query($wp_link, "Select post_id FROM pc_postmeta WHERE meta_value = '{$wpGameID}'")->fetch_object();

        # Определяем статус поста игры (ищем статус private)
        $checkPrivateStatus = mysqli_query( $wp_link, "SELECT post_status FROM pc_posts WHERE pc_posts.ID = '{$resultCheck->post_id}';" )->fetch_object();

        # Если статус private, то получаем статус активности игры
        if ( $checkPrivateStatus->post_status === 'private' )
        {
            $activeStatus = mysqli_query($wp_link, "SELECT meta_value FROM pc_postmeta WHERE meta_key = 'game_active' AND post_id = '{$resultCheck->post_id}';")->fetch_object();

            # Если статус активен, то проверяем рейтинг игры, для дальнейшего переноса поста игры или в опубликованное или в черновики
            if ( $activeStatus->meta_value === '1' )
            {
                $gameRating = mysqli_query($wp_link, "SELECT meta_value FROM pc_postmeta WHERE meta_key = 'game_ratingGame' AND post_id = '{$resultCheck->post_id}';")->fetch_object();

                # Если рейтинг есть, пост игры отправляем в опубликованное
                if ( !empty ($gameRating->meta_value) )
                {
                    mysqli_query($wp_link, "UPDATE pc_posts SET post_status = 'publish' WHERE pc_posts.ID = '{$resultCheck->post_id}';");
                }
                # Если рейтинга нет, отправляем пост в черновики
                else
                {
                    mysqli_query($wp_link, "UPDATE pc_posts SET post_status = 'draft' WHERE pc_posts.ID = '{$resultCheck->post_id}';");
                }
            }
        }
    }
}

mysqli_close($wp_link);
mysqli_close($parser_link);

// Полное время обновления БД сайта
printf('Скрипт выполнялся %.4F сек.', (microtime(true) - $startInsertTime));