<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class ToTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::toType();
        $this->assertSame(7, $s->sanitize(7));
        $this->assertSame(['foo'], $s->sanitize(['foo']));
    }
    public function testToType()
    {
        $s = Sanitize::toType();
        $this->assertSame(0, $s->sanitize('0'));
        $this->assertSame(1, $s->sanitize('1'));
        $this->assertSame(1, $s->sanitize(1));
        $this->assertSame(0.0, $s->sanitize(0.0));
        $this->assertSame(7.5, $s->sanitize(7.5));
        $this->assertSame(7, $s->sanitize('7'));
        $this->assertSame(7.5, $s->sanitize('7.5'));
        $this->assertSame(null, $s->sanitize('null'));
        $this->assertSame(false, $s->sanitize('false'));
        $this->assertSame(true, $s->sanitize('true'));
        $this->assertSame('foo', $s->sanitize('foo'));
        $this->assertSame('7foo', $s->sanitize('7foo'));
    }
} 