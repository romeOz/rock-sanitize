<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class TruncateWordsTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::truncateWords();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testTruncateWords()
    {
        $s = Sanitize::truncateWords(7);
        $this->assertSame('Hello...', $s->sanitize('Hello world!'));
        $this->assertSame('Привет...', $s->sanitize('Привет мир!'));
    }
} 