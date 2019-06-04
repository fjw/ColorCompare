<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
use ColorCompare\Color;

// -----------------------------

function rnd_hex()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function rnd_color()
{
    return "#".rnd_hex().rnd_hex().rnd_hex();
}

// -----------------------------

$main_color = new Color(rnd_color());

$colors = [];
for ($i = 0; $i <= 1000; $i++) {

    $hex = rnd_color();

    $color = new Color($hex);
    $difference = round($main_color->getDifference($color), 2);

    $colors[$hex] = $difference;
}

asort($colors);

?>

<html lang="en">
<head>
    <title>ColorCompare Example</title>
    <link href="https://fonts.googleapis.com/css?family=Rajdhani&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rajdhani', sans-serif;
            font-size: 20px;
            font-weight: bold;
            color: #fff;
        }

        div {
            text-align: center;

            width: 100%;
            margin: 12px;
            padding: 2px;
            border-radius: 4px;
        }
    </style>
</head>
<body style="background-color: <?= $main_color->getHex(); ?>">

<h3>reload page to randomize again...</h3>

<?php
foreach ($colors as $hex => $difference) {
    ?>

    <div style="background-color: <?= $hex ?>"><?= $difference ?></div>

    <?php
}
?>

</body>
</html>
