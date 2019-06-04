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

        $this->assertEquals(57, $color->getLab()["l"]);
        $this->assertEquals(86, $color->getLab()["a"]);
        $this->assertEquals(-17, $color->getLab()["b"]);
    }

}
