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

</body>
</html>
