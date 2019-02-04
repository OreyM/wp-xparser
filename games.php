<?php
require_once 'config/config.php';
require_once 'scripts/form_games.php';
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Формирование таблицы</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.min.css">
</head>
<body>

<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
<![endif]-->



<section class="games-table">
    <table>
        <thead>
        <tr>
            <th>№</th>
            <th>ID</th>
            <th>Game name</th>
            <th>Site link</th>
            <th>Country</th>
            <th>Price</th>
            <th>BfDsc Price</th>
            <th>Discount</th>
            <th>Type</th>
            <th>Free game</th>
            <th>Game link</th>
            <th>Country-2</th>
            <th>Price-2</th>
            <th>Country-3</th>
            <th>Price-3</th>
            <th>Country-4</th>
            <th>Price-4</th>
            <th>Country-5</th>
            <th>Price-5</th>
        </tr>
        </thead>

        <tbody>
        <?php $count = 1; ?>
        <?php foreach ($gamesArray as $gameID => $gameData): ?>
        <tr>
            <td><?php echo $count++; ?></td>
            <td><?php echo $gameData['game_id']; ?></td>
            <td class="game-name"><?php echo $gameData['game_name']; ?></td>
            <td class="link"><a href="<?php echo $gameData['site_link']; ?>">link</a></td>
            <td><?php echo $gameData['country']; ?></td>
            <td class="price"><?php echo $gameData['country_price']; ?></td>
            <td class="discount-class"><?php echo $gameData['before_discount']; ?></td>
            <td>
                <?php if ($gameData['discount']): ?>
                    <span class="bool yes">YES</span>
                <?php else: ?>
                    <span class="bool no">NO</span>
                <?php endif; ?>
            </td>
            <td><?php echo $gameData['discount_type']; ?></td>
            <td>
                <?php if ($gameData['free_game']): ?>
                    <span class="bool yes">YES</span>
                <?php else: ?>
                    <span class="bool no">NO</span>
                <?php endif; ?>
            </td>
            <td class="link"><a href="<?php echo $gameData['game_link']; ?>">link</a></td>
            <td><?php echo $gameData['next_ctr_1']; ?></td>
            <td class="check-another">
                <a href="<?php echo $gameData['next_link_1']; ?>"><?php echo $gameData['next_prc_1']; ?></a>
            </td>
            <td><?php echo $gameData['next_ctr_2']; ?></td>
            <td class="check-another">
                <a href="<?php echo $gameData['next_link_2']; ?>"><?php echo $gameData['next_prc_2']; ?></a>
            </td>
            <td><?php echo $gameData['next_ctr_3']; ?></td>
            <td class="check-another">
                <a href="<?php echo $gameData['next_link_3']; ?>"><?php echo $gameData['next_prc_3']; ?></a>
            </td>
            <td><?php echo $gameData['next_ctr_4']; ?></td>
            <td class="check-another">
                <a href="<?php echo $gameData['next_link_4']; ?>"><?php echo $gameData['next_prc_4']; ?></a>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>

    </table>
</section>

<section id="add-data-to-site" style="margin-top: 80px;">
    <a href="scripts/wp_content.php" class="btn btn-color-orange"
       style="width: 400px;height: 80px;">ADD DATA TO SITE!!!!</a>
</section>


<script src="" async defer></script>
</body>
</html>