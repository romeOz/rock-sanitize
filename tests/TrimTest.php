<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class TrimTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::trim();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testTrim()
    {
        $this->assertSame('foo',  Sanitize::trim()->sanitize(' foo  '));
    }
} 