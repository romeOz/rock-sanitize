<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class NumbersTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::numbers();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testNumbers()
    {
        $s = Sanitize::numbers();
        $this->assertSame('4', $s->sanitize('special4you'));
        $this->assertSame('', $s->sanitize('hello world'));
    }
} 