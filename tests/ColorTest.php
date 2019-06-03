<?php

namespace ColorCompare;

use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{

    public function testWorld()
    {

        $this->assertEquals(
              "Hello World",
            Color::world()
        );

    }
}
