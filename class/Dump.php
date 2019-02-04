<?php
class Dump {

    public function __construct () {

    }

    public function getDump ($dumpData) {
        echo '<pre>';
        var_dump($dumpData);
        echo '</pre>';
    }
}