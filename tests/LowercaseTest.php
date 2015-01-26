<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class LowercaseTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::lowercase();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testLowercase()
    {
        $s = Sanitize::lowercase();
        $this->assertSame('foo', $s->sanitize('FOO'));
        $this->assertSame('абг', $s->sanitize('АБГ'));
    }
} 