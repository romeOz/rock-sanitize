<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class NoiseWordsTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::noiseWords();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testNoiseWords()
    {
        $s = Sanitize::noiseWords();
        $this->assertSame('made France', $s->sanitize('made by France'));
        $this->assertSame('hello world!', $s->sanitize('hello world!'));
    }

    public function testCustomNoiseWords()
    {
        $s = Sanitize::noiseWords('hello');
        $this->assertSame('world!', $s->sanitize('hello world!'));
    }
} 