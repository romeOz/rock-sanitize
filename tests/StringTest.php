<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testString()
    {
        $s = Sanitize::string();
        $this->assertSame('1', $s->sanitize(1));
        $this->assertSame('0', $s->sanitize('0'));
        $this->assertSame('7.5', $s->sanitize(7.5));
        $this->assertSame('foo', $s->sanitize('foo'));
    }
} 