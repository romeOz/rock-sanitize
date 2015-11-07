<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class IntRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testInt()
    {
        $s = Sanitize::int()->setRecursive(false);
        $this->assertSame(1, $s->sanitize(['foo']));
        $this->assertSame(1, $s->sanitize(1.0));
        $this->assertSame(1, $s->sanitize('1'));
        $this->assertSame(0, $s->sanitize('foo'));
        $this->assertSame(0, $s->sanitize(''));
        $this->assertSame(0, $s->sanitize([]));
        $this->assertSame(0, $s->sanitize('0.0'));
        $this->assertSame(0, $s->sanitize(0));
    }
} 