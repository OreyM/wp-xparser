<?php
require_once 'config/config.php';
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo VERSION ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.min.css">
</head>
<body>

<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<a href="general.php" class="btn btn-color-red"
   style="position: absolute;width: 182px;left: 8px;top: 8px;z-index: 9999;">GeneralData Form</a>

<a href="scripts/wp_content.php" class="btn btn-color-orange"
   style="position: absolute;width: 182px;left: 8px;top: 72px;z-index: 9999;">WP</a>

<section id="general" style="margin-top: 70px;">
    <div id="data-wrapp">
        <div id="title-wrapp">
            <h1><?php echo VERSION ?></h1>
        </div>
        <div id="button-wrapp">
            <a href="start.php" class="btn btn-color-red">start parsing</a>
            <a href="games.php" class="btn btn-color-blue">form table</a>
            <a href="result.php" class="btn btn-color-orange">chek result</a>
        </div>
        <div id="discount-wrapp">
            <h4 class="discount-title">Get games discount</h4>
            <div class="form-wrapp">
                <form action="discount.php" method="post">
                    <div class="input-wrapp">
                        <input class="input-url" type="text" placeholder="Enter url for parsing" name="urlParsing">
                        <input type="submit" class="btn btn-color-green" value="take discount">
                    </div>
                </form>
            </div>
        </div>
        <div id="discount-wrapp" style="margin-top: 65px;">
            <h4 class="discount-title">Get games recomend</h4>
            <div class="form-wrapp">
                <form action="recomend.php" method="post">
                    <div class="input-wrapp">
                        <input class="input-url" type="text" placeholder="Enter url for parsing" name="urlParsingRecomend">
                        <input type="submit" class="btn btn-color-orange" value="take recomandation">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="" async defer></script>
</body>
</html>