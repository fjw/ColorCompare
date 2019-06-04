<?php
namespace ColorCompare;

use \Exception;

class Color
{

    private $hex = null;
    private $rgb = null;
    private $hsl = null;
    private $lab = null;
    private $din99 = null;

    /**
     * Color constructor.
     *
     * The value parameter can be:
     *  - a hexadecimal color value, example: "#a0f3ee", "#ccc"
     *  - a RGB color array, example: ["r" => 255, "g" => 0, "b" => 100]
     *  - a HSL color array, example: ["h" => 360, "s" => 0.8, "l" => 1]
     *  - a CIELAB color array, example: ["L" => 100, "a" => -120, "b" => 75]
     *
     * @param  string|array  $value
     * @throws  Exception
     */
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

            } else {
                throw new Exception("Unknown Color Format");
            }

        } else {

            throw new Exception("Unknown Color Format");

        }
    }

    /**
     * Resets the color, making it empty.
     */
    private function reset()
    {
        $this->hex = null;
        $this->rgb = null;
        $this->hsl = null;
        $this->lab = null;
        $this->din99 = null;
    }

    /**
     * Validates and sets the color from a Hex color.
     *
     * @param  string  $hex
     * @throws  Exception
     */
    private function setHex($hex)
    {
        $this->reset();

        if (!is_string($hex) ||
            strpos($hex, "#") !== 0 ||
            (strlen($hex) !== 4 && strlen($hex) !== 7)) {
            throw new Exception("Wrong Hex Format");
        }

        if (!preg_match("/#([a-f0-9]{3}){1,2}/i", $hex)) {
            throw new Exception("Wrong Hex Format");
        }

        if (strlen($hex) === 4) {
            $a = sscanf($hex, "#%01s%01s%01s");
            $hex = sprintf("#%s%s%s", $a[0].$a[0], $a[1].$a[1], $a[2].$a[2]);
        }

        $this->hex = strtolower($hex);
    }

    /**
     * Validates and sets the color from a RGB color.
     *
     * @param  array  $rgb
     * @throws  Exception
     */
    private function setRgb($rgb)
    {
        $this->reset();

        if (!is_array($rgb) ||
            count($rgb) !== 3 ||
            !array_key_exists("r", $rgb) ||
            !array_key_exists("g", $rgb) ||
            !array_key_exists("b", $rgb)) {

            throw new Exception("Wrong RGB Format");
        }

        $this->rgb = $rgb;
    }

    /**
     * Validates and sets the color from a HSL color.
     *
     * @param  array  $hsl
     * @throws  Exception
     */
    private function setHsl($hsl)
    {
        $this->reset();

        if (!is_array($hsl) ||
            count($hsl) !== 3 ||
            !array_key_exists("h", $hsl) ||
            !array_key_exists("s", $hsl) ||
            !array_key_exists("l", $hsl)) {

            throw new Exception("Wrong HSL Format");
        }

        $this->hsl = $hsl;
    }

    /**
     * Validates and sets the color from a LAB color.
     *
     * @param  array  $lab
     * @throws  Exception
     */
    private function setLab($lab)
    {
        $this->reset();

        if (!is_array($lab) ||
            count($lab) !== 3 ||
            !array_key_exists("L", $lab) ||
            !array_key_exists("a", $lab) ||
            !array_key_exists("b", $lab)) {

            throw new Exception("Wrong CIELAB Format");
        }

        $this->lab = $lab;
    }

    // -------------------------------------------------------------------------------------------

    /**
     * Gets the color as RGB array.
     *
     * r: 0-255, g: 0-255, b: 0-255
     *
     * example: ["r" => 255, "g" => 0, "b" => 100]
     *
     * @return  array
     */
    public function getRgb()
    {

        if ($this->rgb) {
            return $this->rgb;
        }

        if ($this->hex) {
            $this->rgb = $this::hexToRgb($this->hex);
            return $this->rgb;
        }

        if ($this->hsl) {
            $this->rgb = $this::hslToRgb($this->hsl);
            return $this->rgb;
        }

        if ($this->lab) {
            $this->rgb = $this::labToRgb($this->lab);
            return $this->rgb;
        }

        return $this->rgb;
    }

    /**
     * Gets the color as hexadecimal string.
     *
     * example: "#a0f3ee"
     *
     * @return  string
     */
    public function getHex()
    {
        if ($this->hex) {
            return $this->hex;
        }

        $this->hex = $this::rgbToHex($this->getRgb());
        return $this->hex;
    }

    /**
     * Gets the color as HSL array.
     *
     * h: 0-360, s: 0-1, l: 0-1
     *
     * example: ["h" => 360, "s" => 0.8, "l" => 1]
     *
     * @return  array
     */
    public function getHsl()
    {

        if ($this->hsl) {
            return $this->hsl;
        }

        $this->hsl = $this::rgbToHsl($this->getRgb());
        return $this->hsl;
    }

    /**
     * Gets the color as CIELAB array.
     *
     * L: 0-100, a: approx. -86 - 98, b: approx. -108 - 94
     *
     * example: ["L" => 100, "a" => -120, "b" => 75]
     *
     * @return  array
     */
    public function getLab()
    {

        if ($this->lab) {
            return $this->lab;
        }

        $this->lab = $this::rgbToLab($this->getRgb());
        return $this->lab;
    }

    /**
     * Gets the color as DIN-99 array.
     *
     * L: 0-100, a: approx. -50 - 40, b: approx. -40 - 40
     *
     * example: ["L99" => 100, "a99" => -120, "b99" => 75]
     *
     * @return  array
     */
    public function getDin99()
    {

        if ($this->din99) {
            return $this->din99;
        }

        $this->din99 = $this::labToDin99($this->getLab());
        return $this->din99;
    }

    // -------------------------------------------------------------------------------------------

    /**
     * Converts Hex to RGB
     *
     * @param  string  $hex
     * @return  array
     */
    private static function hexToRgb($hex)
    {
        sscanf($hex, "#%02x%02x%02x", $r, $g, $b);

        return ["r" => $r, "g" => $g, "b" => $b];
    }

    /**
     * Converts RGB to Hex
     *
     * @param  array  $rgb
     * @return  string
     */
    private static function rgbToHex($rgb)
    {

        return sprintf("#%02x%02x%02x", $rgb["r"], $rgb["g"], $rgb["b"]);
    }

    /**
     * Converts RGB to HSL
     *
     * @param  array  $rgb
     * @return  array
     */
    private static function rgbToHsl($rgb)
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
                $s = $delta / ($max + $min);
            } else {
                $s = $delta / (2 - $max - $min);
            }

            $delta_r = ((($max - $r) / 6) + ($delta / 2)) / $delta;
            $delta_g = ((($max - $g) / 6) + ($delta / 2)) / $delta;
            $delta_b = ((($max - $b) / 6) + ($delta / 2)) / $delta;

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


        return ["h" => $h * 360, "s" => $s, "l" => $l];
    }

    /**
     * Converts HSL to RGB
     *
     * @param  array  $hsl
     * @return  array
     */
    private static function hslToRgb($hsl)
    {
        $h = $hsl["h"] / 360;
        $s = $hsl["s"];
        $l = $hsl["l"];

        $t = function ($e, $f, $k) {

            if ($k < 0) {
                $k += 1;
            }
            if ($k > 1) {
                $k -= 1;
            }
            if ((6 * $k) < 1) {
                return $e + ($f - $e) * 6 * $k;
            }
            if ((2 * $k) < 1) {
                return $f;
            }
            if ((3 * $k) < 2) {
                return $e + ($f - $e) * ((2 / 3) - $k) * 6;
            }
            return $e;
        };

        if ($s === 0) { // gray
            $r = $l * 255;
            $g = $l * 255;
            $b = $l * 255;
        } else {

            if ($l < 0.5) {
                $f = $l * (1 + $s);
            } else {
                $f = ($l + $s) - ($s * $l);
            }

            $e = 2 * $l - $f;

            $r = 255 * $t($e, $f, $h + (1 / 3));
            $g = 255 * $t($e, $f, $h);
            $b = 255 * $t($e, $f, $h - (1 / 3));

        }

        return ["r" => intval(round($r)), "g" => intval(round($g)), "b" => intval(round($b))];
    }

    /**
     * Converts RGB to CIELAB
     *
     * @param  array  $rgb
     * @return  array
     */
    private static function rgbToLab($rgb)
    {

        // ----- RGB to XYZ

        $r = $rgb["r"] / 255;
        $g = $rgb["g"] / 255;
        $b = $rgb["b"] / 255;

        if ($r > 0.04045) {
            $r = (($r + 0.055) / 1.055) ** 2.4;
        } else {
            $r = $r / 12.92;
        }

        if ($g > 0.04045) {
            $g = (($g + 0.055) / 1.055) ** 2.4;
        } else {
            $g = $g / 12.92;
        }

        if ($b > 0.04045) {
            $b = (($b + 0.055) / 1.055) ** 2.4;
        } else {
            $b = $b / 12.92;
        }

        $r = $r * 100;
        $g = $g * 100;
        $b = $b * 100;

        $x = $r * 0.4124 + $g * 0.3576 + $b * 0.1805;
        $y = $r * 0.2126 + $g * 0.7152 + $b * 0.0722;
        $z = $r * 0.0193 + $g * 0.1192 + $b * 0.9505;


        $x = $x / 95.047; // D65/2
        $y = $y / 100;
        $z = $z / 108.883;

        // ----- XYZ to CIE-L*ab

        if ($x > 0.008856) {
            $x = $x ** (1 / 3);
        } else {
            $x = (7.787 * $x) + (16 / 116);
        }

        if ($y > 0.008856) {
            $y = $y ** (1 / 3);
        } else {
            $y = (7.787 * $y) + (16 / 116);
        }

        if ($z > 0.008856) {
            $z = $z ** (1 / 3);
        } else {
            $z = (7.787 * $z) + (16 / 116);
        }

        $l = (116 * $y) - 16;
        $a = 500 * ($x - $y);
        $b = 200 * ($y - $z);


        return ["L" => $l, "a" => $a, "b" => $b];
    }

    /**
     * Converts CIELAB to RGB
     *
     * @param  array  $lab
     * @return  array
     */
    private static function labToRgb($lab)
    {

        $y = ($lab["L"] + 16) / 116;
        $x = $lab["a"] / 500 + $y;
        $z = $y - $lab["b"] / 200;

        if ($y ** 3 > 0.008856) {
            $y = $y ** 3;
        } else {
            $y = ($y - 16 / 116) / 7.787;
        }

        if ($x ** 3 > 0.008856) {
            $x = $x ** 3;
        } else {
            $x = ($x - 16 / 116) / 7.787;
        }

        if ($z ** 3 > 0.008856) {
            $z = $z ** 3;
        } else {
            $z = ($z - 16 / 116) / 7.787;
        }

        $x = $x * 95.047; // D65/2
        $y = $y * 100;
        $z = $z * 108.883;

        $x /= 100;
        $y /= 100;
        $z /= 100;

        $r = $x * 3.2406 + $y * -1.5372 + $z * -0.4986;
        $g = $x * -0.9689 + $y * 1.8758 + $z * 0.0415;
        $b = $x * 0.0557 + $y * -0.2040 + $z * 1.0570;

        if ($r > 0.0031308) {
            $r = 1.055 * ($r ** (1 / 2.4)) - 0.055;
        } else {
            $r = 12.92 * $r;
        }

        if ($g > 0.0031308) {
            $g = 1.055 * ($g ** (1 / 2.4)) - 0.055;
        } else {
            $g = 12.92 * $g;
        }

        if ($b > 0.0031308) {
            $b = 1.055 * ($b ** (1 / 2.4)) - 0.055;
        } else {
            $b = 12.92 * $b;
        }

        return ["r" => intval(round($r * 255)), "g" => intval(round($g * 255)), "b" => intval(round($b * 255))];
    }

    /**
     * Converts CIELAB to DIN-99
     *
     * @param  array  $lab
     * @return  array
     */
    private static function labToDin99($lab)
    {

        $L99 = 105.51 * log(1 + 0.0158 * $lab["L"]);

        $a = $lab["a"];
        $b = $lab["b"];

        if ($a === 0 && $b === 0) {

            $a99 = 0;
            $b99 = 0;

        } else {

            $cos16 = cos(deg2rad(16));
            $sin16 = sin(deg2rad(16));

            $e = $a * $cos16 + $b * $sin16;
            $f = 0.7 * ($b * $cos16 - $a * $sin16);
            $g = 0.045 * sqrt($e ** 2 + $f ** 2);
            $k = log(1 + $g) / $g;

            $a99 = $k * $e;
            $b99 = $k * $f;

        }

        return ["L99" => $L99, "a99" => $a99, "b99" => $b99];
    }
}
