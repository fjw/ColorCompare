<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/../vendor/autoload.php';



$color = new \ColorCompare\Color("#ff00aa");

$din = $color->getDin99();

dump($color);
?>
<style>
    body {
      background-color: #f0a;
    }
</style>