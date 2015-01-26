<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class SpecialCharsTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::specialChars();
        $this->assertSame(7, $s->sanitize(7));
    }
    public function testSpecialChars()
    {
        $s = Sanitize::specialChars();
        $this->assertSame('Hello world', $s->sanitize('Hello world!'));
        $this->assertSame('Hello world', $s->sanitize('«Hello world!»'));
        $this->assertSame('foo', $s->sanitize('foo'));
    }
} 