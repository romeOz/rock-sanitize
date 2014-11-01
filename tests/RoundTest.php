<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class RoundTest extends \PHPUnit_Framework_TestCase
{
    public function testRound()
    {
        $s = Sanitize::round();
        $this->assertSame(8.0, $s->sanitize('7.7'));
    }
} 