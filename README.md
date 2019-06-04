# ColorCompare


[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://travis-ci.com/fjw/ColorCompare.svg?branch=master)](https://travis-ci.com/fjw/ColorCompare)

ColorCompare is a library to convert colors from hex, RGB, HSL, CIE L\*a\*b\* (LAB) and DIN-99 into one another.


You can get the library from [packagist](https://packagist.org/packages/fjw/color-compare):
```
composer require fjw/color-compare
```

## How to use

You can get the visual difference (distance) of two colors easily:
```php
use ColorCompare\Color;

$color1 = new Color("#aaff05");
$color2 = new Color("#CCC");

$difference = $color1->getDifference($color2);
```

You can convert each format into one another:
```php
use ColorCompare\Color;

$color = new Color("#aaff05");

$hex = $color->getHex(); // just to show off, it already was Hex ;)
$rgb = $color->getRgb();
$hsl = $color->getHsl();
$lab = $color->getLab();
$din99 = $color->getDin99();
```

You can create the color object by Hex, RGB, HSL and LAB:
```php
use ColorCompare\Color;

$color = new Color([
    "h" => 300,
    "s" => 0.5,
    "l" => 1 
]);

$hex = $color->getHex();
```

## Visual Color Distance with DIN-99
DIN-99 differences can better calculate the human visual difference than LAB with delta-E. There are also superior distance calculations like CIE94 or CIEDE2000 but these are complicated and need intensive calculations.
With DIN-99 the calculation is done beforehand and needs less ressources. When your color is already converted into DIN-99 you can just calculate the euklidean distance and get the same quality.

```php
sqrt(($c2["L99"] - $c1["L99"])**2 +
    ($c2["a99"] - $c1["a99"])**2 +
    ($c2["b99"] - $c1["b99"])**2);
``` 

This is a huge advantage! If you would like, per example, make a client side filter of colored products (or whatever) you can convert your data into DIN-99 on the server side and only need to do the easier euklidean calculation in your JavaScript.

Sources (german):

http://www.germancolorgroup.de/html/Vortr_02_pdf/GCG_%202002_%20Buering.pdf

https://de.wikipedia.org/wiki/DIN99-Farbraum

## Example Code
Just run ```./devserver.sh``` if you have PHP-CLI and open ```http://localhost:8000``` to see the difference calculation in action.
