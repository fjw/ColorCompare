<?php

namespace ColorCompare;

use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{


    public function testHexFormat1()
    {
        $this->expectException(\Exception::class);
        new Color("#1");
    }

    public function testHexFormat2()
    {
        $this->expectException(\Exception::class);
        new Color("#1234");
    }

    public function testHexFormat3()
    {
        $this->expectException(\Exception::class);
        new Color("#1234567");
    }

    public function testHexShort()
    {
        $color = new Color("#AbC");
        $this->assertEquals("#aabbcc", $color->getHex());
    }

    public function testHexToRgb()
    {
        $color = new Color("#ff00aa");

        $this->assertEquals(255, $color->getRgb()["r"]);
        $this->assertEquals(0, $color->getRgb()["g"]);
        $this->assertEquals(170, $color->getRgb()["b"]);
    }

}
