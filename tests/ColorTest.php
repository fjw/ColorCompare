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

        $this->assertEquals(56.25, round($color->getLab()["L"], 2));
        $this->assertEquals(88.12, round($color->getLab()["a"], 2));
        $this->assertEquals(-18.84, round($color->getLab()["b"], 2));
    }

    public function testGetDin99()
    {
        $color = new Color("#ff00aa");

        $this->assertEquals(67.10, round($color->getDin99()["L99"], 2));
        $this->assertEquals(32.74, round($color->getDin99()["a99"], 2));
        $this->assertEquals(-12.22, round($color->getDin99()["b99"], 2));
    }

    /**
     * @throws Exception
     */
    public function testConvertToHSLandBack()
    {
        $hex = "#ff00aa";

        $c1 = new Color($hex);

        $hsl = $c1->getHsl();

        $c2 = new Color($hsl);

        $this->assertEquals($hex, $c2->getHex());
    }

    /**
     * @throws Exception
     */
    public function testConvertToLABandBack()
    {
        $hex = "#ff00aa";

        $c1 = new Color($hex);

        $lab = $c1->getLab();

        $c2 = new Color($lab);

        $this->assertEquals($hex, $c2->getHex());
    }

    public function testGetDifference()
    {
        $color1 = new Color("#ff00aa");
        $color2 = new Color("#afc");

        $this->assertEquals(62.75, round($color1->getDifference($color2), 2));
    }

    public function testGetDifference2()
    {
        $color1 = new Color("#fff");
        $color2 = new Color("#000");

        $this->assertEquals(100, round($color1->getDifference($color2), 2));
    }
}
