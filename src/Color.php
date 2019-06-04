<?php
namespace ColorCompare;

class Color
{

    private $hex = null;
    private $rgb = null;
    private $hsl = null;
    private $lab = null;
    private $din99 = null;

    public function __construct($value)
    {
        if (is_string($value) && strpos($value, "#") === 0) {
            $this->setHex($value);
        } elseif (is_array($value) && count($value) === 3) {
            $firstkey = array_keys($value)[0];

            if ($firstkey === "r") {

                $this->setRgb($value);

            } elseif ($firstkey === "h") {

                $this->setHsl($value);

            } elseif ($firstkey === "L") {

                $this->setLab($value);

            } elseif ($firstkey === "L99") {

                $this->setDin99($value);

            }

        } else {
            throw new \Exception("Unknown Color Format");
        }
    }

    public function reset()
    {
        $this->hex = null;
        $this->rgb = null;
        $this->hsl = null;
        $this->lab = null;
        $this->din99 = null;
    }

    public function setHex($hex)
    {
        $this->reset();

        if (!is_string($hex) ||
            strpos($hex, "#") !== 0 ||
            (strlen($hex) !== 4 && strlen($hex) !== 7)) {
            throw new \Exception("Wrong Hex Format");
        }

        if (!preg_match("/#([a-f0-9]{3}){1,2}/i", $hex)) {
            throw new \Exception("Wrong Hex Format");
        }

        if (strlen($hex) === 4) {
            $a = sscanf($hex, "#%01s%01s%01s");
            $hex = sprintf("#%s%s%s", $a[0].$a[0], $a[1].$a[1], $a[2].$a[2]);
        }

        $this->hex = strtolower($hex);
    }

    public function getHex()
    {
        return $this->hex;
    }

    public function getRgb()
    {

        if ($this->rgb) {
            return $this->rgb;
        }

        sscanf($this->hex, "#%02x%02x%02x", $r, $g, $b);

        $this->rgb = ["r" => $r, "g" => $g, "b" => $b];

        return $this->rgb;
    }

    public function getHsl()
    {

        if ($this->hsl) {
            return $this->hsl;
        }

        $this->getRgb();
        return $this->rgbToHsl();
    }

    public function getLab()
    {

        if ($this->lab) {
            return $this->lab;
        }

        $this->getRgb();
        return $this->rgbToLab();
    }

    // -------------------------------------------------------------------------------------------

    private static function rgbToHex($rgb)
    {

        return sprintf("#%02x%02x%02x", $rgb["r"], $rgb["g"], $rgb["b"]);
    }

    private function rgbToHsl($rgb)
    {
        $r = $rgb["r"] / 255;
        $g = $rgb["g"] / 255;
        $b = $rgb["b"] / 255;

        $min = min($r, $g, $b);
        $max = max($r, $g, $b);

        $delta = $max - $min;

        $l = ($max + $min) / 2;

        if ($delta === 0) { // gray
            $h = 0;
            $s = 0;
        } else {
            if ($l < 0.5) {
                $s = $delta / ( $max + $min );
            } else {
                $s = $delta / ( 2 - $max - $min );
            }

            $delta_r = ( ( ($max - $r) / 6 ) + ($delta / 2) ) / $delta;
            $delta_g = ( ( ($max - $g) / 6 ) + ($delta / 2) ) / $delta;
            $delta_b = ( ( ($max - $b) / 6 ) + ($delta / 2) ) / $delta;

            $h = 0;

            if ($r === $max) {
                $h = $delta_b - $delta_g;
            } elseif ($g === $max) {
                $h = (1 / 3) + $delta_r - $delta_b;
            } elseif ($b === $max) {
                $h = (2 / 3) + $delta_g - $delta_r;
            }

            if ($h < 0) {
                $h += 1;
            }
            if ($h > 1) {
                $h -= 1;
            }
        }


        return [ "h" => $h*360, "s" => $s, "l" => $l];
    }

    private static function rgbToLab($rgb)
    {

        // ----- RGB to XYZ

        $r = $rgb["r"] / 255;
        $g = $rgb["g"] / 255;
        $b = $rgb["b"] / 255;

        if ($r > 0.04045) {
            $r = ( ( $r + 0.055 ) / 1.055 )**2.4;
        } else {
            $r = $r / 12.92;
        }

        if ($g > 0.04045) {
            $g = ( ( $g + 0.055 ) / 1.055 )**2.4;
        } else {
            $g = $g / 12.92;
        }

        if ($b > 0.04045) {
            $b = ( ( $b + 0.055 ) / 1.055 )**2.4;
        } else {
            $b = $b / 12.92;
        }

        $r = $r * 100;
        $g = $g * 100;
        $b = $b * 100;

        $x = $r * 0.4124 + $g * 0.3576 + $b * 0.1805;
        $y = $r * 0.2126 + $g * 0.7152 + $b * 0.0722;
        $z = $r * 0.0193 + $g * 0.1192 + $b * 0.9505;

        // ----- XYZ to CIE-L*ab

        $rx = $ry = $rz = 100; // Equal energy (http://www.easyrgb.com/en/math.php)

        $x = $x / $rx;
        $y = $y / $ry;
        $z = $z / $rz;

        if ($x > 0.008856) {
            $x = $x**( 1/3 );
        } else {
            $x = ( 7.787 * $x ) + ( 16 / 116 );
        }

        if ($y > 0.008856) {
            $y = $y**( 1/3 );
        } else {
            $y = ( 7.787 * $y ) + ( 16 / 116 );
        }

        if ($z > 0.008856) {
            $z = $z**( 1/3 );
        } else {
            $z = ( 7.787 * $z ) + ( 16 / 116 );
        }

        $l = ( 116 * $y ) - 16;
        $a = 500 * ( $x - $y );
        $b = 200 * ( $y - $z );


        return ["L" => $l, "a" => $a, "b" => $b];
    }
}
