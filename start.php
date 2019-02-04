<?php
require_once 'config/config.php';
require_once 'scripts/parsing.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main.min.css">
    <title>Parsing games</title>
</head>
<body>

<div class="parsing">
    <div class="parsing-info">
        <span>Parsing total Time</span>
        <strong><?php printf('%.0F', $finishPArsingTime); ?> sec</strong>
    </div>
</div>

<div class="container">
    <div class="row">

        <?php foreach ($parsingInfo as $info): ?>
        <div class="country-parsing-status">
            <div class="game-wrapp">
                <div class="title-wrapp">
                    <h1 class="title">Parsing <?php echo $info['country']; ?></h1>
                    <h3 class="games-count"><?php echo $info['totalGames']; ?> games</h3>
                    <div class="total-time-wrapp">
                        <h4 class="time">
                            <span>total time</span> - <strong><?php printf('%.0F', $info['parsingTime']); ?> sec</strong>
                        </h4>
                    </div>
                </div>
                <div class="data-wrapp">
                    <h3 class="new-games">new games received</h3>
                    <?php $newGamesCount = 1; ?>
                    <?php if( !empty($info['newGames']) ): ?>
                    <?php foreach ($info['newGames'] as $gameTitle => $gameLink): ?>
                    <p class="game-list">
                        <strong><?php echo $newGamesCount++; ?>. </strong>
                        <a href="<?php echo $gameLink; ?>" target="_blank"><?php echo $gameTitle; ?></a>
                    </p>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>


<!--<style>-->
<!--    th, td {-->
<!--        border-bottom: 2px solid;-->
<!--        padding: 6px 0;-->
<!--    }-->
<!--</style>-->

<!--    <table border="1"">-->
<!--        <tr style="background-color: #212121;color: #fff;">-->
<!--            <th style="width: 52px;">№</th>-->
<!--            <th>Country</th>-->
<!--            <th style="width: 122px;">Game Price</th>-->
<!--            <th style="width: 122px;">BFD Price</th>-->
<!--            <th>Free Game</th>-->
<!--            <th>Discount</th>-->
<!--            <th>Game ID</th>-->
<!--            <th>Game Name</th>-->
<!--            <th>Recommend</th>-->
<!--<!--            <th>Prod Desc</th>-->-->
<!--        </tr>-->
<!--        --><?php //$count = 1; ?>
<!--        --><?php //foreach ($readyData as $data): ?>
<!--        <tr>-->
<!--            <td style="text-align: center;">--><?php //echo $count++; ?><!--</td>-->
<!--            <td>--><?php //echo $data['country']; ?><!--</td>-->
<!--            <td>--><?php //echo $data['game_price']; ?><!--</td>-->
<!--            <td>--><?php //echo $data['before_discount']; ?><!--</td>-->
<!--            <td>-->
<!--                --><?php //if ($data['free_game']): ?>
<!--                <strong>Free game</strong>-->
<!--                --><?php //endif; ?>
<!--            </td>-->
<!--            <td>-->
<!--                --><?php //if ($data['discount']): ?>
<!--                    <strong>--><?php //echo $data['discount_type'] ?><!--</strong>-->
<!--                --><?php //endif; ?>
<!--            </td>-->
<!--            <td>--><?php //echo $data['game_id']; ?><!--</td>-->
<!--            <td><a href="--><?php //echo $data['game_link']; ?><!--" target="_blank">--><?php //echo $data['game_name']; ?><!--</a></td>-->
<!--            <td>--><?php //echo $data['recommend']; ?><!--</td>-->
<!--<!--            <td>-->--><?php ////echo $data['product_description']; ?><!--<!--</td>-->-->
<!--        </tr>-->
<!--        --><?php //endforeach; ?>
<!--    </table>-->

</body>
</html>
