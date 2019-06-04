<?php

namespace ColorCompare;

use PHPUnit\Framework\TestCase;
use \Exception;

class ColorTest extends TestCase
{


    public function testHexFormat1()
    {
        $this->expectException(Exception::class);
        new Color("#1");
    }

    public function testHexFormat2()
    {
        $this->expectException(Exception::class);
        new Color("#1234");
    }

    public function testHexFormat3()
    {
        $this->expectException(Exception::class);
        new Color("#1234567");
    }

    public function testHexShort()
    {
        $color = new Color("#AbC");
        $this->assertEquals("#aabbcc", $color->getHex());
    }

    public function testGetRgb()
    {
        $color = new Color("#ff00aa");

        $this->assertEquals(255, $color->getRgb()["r"]);
        $this->assertEquals(0, $color->getRgb()["g"]);
        $this->assertEquals(170, $color->getRgb()["b"]);
    }

    public function testGetHsl()
    {
        $color = new Color("#ff00aa");

        $this->assertEquals(320, $color->getHsl()["h"]);
        $this->assertEquals(1, $color->getHsl()["s"]);
        $this->assertEquals(0.5, $color->getHsl()["l"]);
    }

    public function testGetLab()
    {
        $color = new Color("#ff00aa");

        $this->assertEquals(56.25, round($color->getLab()["L"],2));
        $this->assertEquals(88.12, round($color->getLab()["a"],2));
        $this->assertEquals(-18.84, round($color->getLab()["b"],2));
    }

}
