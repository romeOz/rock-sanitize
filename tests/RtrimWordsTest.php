<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class RtrimWordsTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::rtrimWords(['foo', 'bar']);
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testRtrimWords()
    {
        $s = Sanitize::rtrimWords(['foo', 'bar']);
        $this->assertSame('text', $s->sanitize('text bar'));
        $this->assertSame('hello world', $s->sanitize('hello world'));
        $this->assertSame('foo hello world!', $s->sanitize('foo hello world!'));
    }
} 