<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class FloatRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testFloat()
    {
        $s = Sanitize::recursive(false)->float();
        $this->assertSame(1.0, $s->sanitize(['foo']));
        $this->assertSame(1.0, $s->sanitize(1));
        $this->assertSame(1.0, $s->sanitize('1'));
        $this->assertSame(0.0, $s->sanitize('foo'));
        $this->assertSame(0.0, $s->sanitize(''));
        $this->assertSame(0.0, $s->sanitize([]));
        $this->assertSame(0.0, $s->sanitize('0'));
        $this->assertSame(0.0, $s->sanitize(0));
    }
} 