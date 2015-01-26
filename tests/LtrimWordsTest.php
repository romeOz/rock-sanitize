<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class LtrimWordsTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::ltrimWords(['foo', 'bar']);
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testLtrimWords()
    {
        $s = Sanitize::ltrimWords(['foo', 'bar']);
        $this->assertSame('text', $s->sanitize('foo text'));
        $this->assertSame('hello world', $s->sanitize('hello world'));
        $this->assertSame('hello world! foo', $s->sanitize('hello world! foo'));
    }
} 