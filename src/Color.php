<?php

namespace ColorCompare;


class Color
{

    private $hex = null;
    private $rgb = null;
    private $hsl = null;
    private $lab = null;
    private $din99 = null;

    function __construct($value)
    {

        if(is_string($value) && strpos($value, "#") === 0) {
            $this->setHex($value);
        } else {
            throw new \Exception("Unknown Color Format");
        }

    }

    public function reset() {

        $this->hex = null;
        $this->rgb = null;
        $this->hsl = null;
        $this->lab = null;
        $this->din99 = null;

    }

    public function setHex($hex) {

        $this->reset();

        if(!is_string($hex) ||
            strpos($hex, "#") !== 0 ||
            (strlen($hex) !== 4 && strlen($hex) !== 7)) {
            throw new \Exception("Wrong Hex Format");
        }

        if(!preg_match("/#([a-f0-9]{3}){1,2}/i", $hex)) {
            throw new \Exception("Wrong Hex Format");
        }

        if(strlen($hex) === 4) {

            $a = sscanf($hex, "#%01s%01s%01s");
            $hex = sprintf("#%s%s%s", $a[0].$a[0], $a[1].$a[1], $a[2].$a[2]);

        }

        $this->hex = strtolower($hex);

    }

    public function getHex() {
        return $this->hex;
    }

    public function getRgb() {

        if($this->rgb) {
            return $this->rgb;
        }

        sscanf($this->hex, "#%02x%02x%02x", $r, $g, $b);

        $this->rgb = ["r" => $r, "g" => $g, "b" => $b];

        return $this->rgb;
    }


}