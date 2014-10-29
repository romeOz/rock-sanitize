<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class LowerFirstTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::lowerFirst();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testLowerFirst()
    {
        $s = Sanitize::lowerFirst();
        $this->assertSame('fOO', $s->sanitize('FOO'));
        $this->assertSame('аБГ', $s->sanitize('АБГ'));
    }
} 