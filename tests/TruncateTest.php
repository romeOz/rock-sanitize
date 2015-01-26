<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class TruncateTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::truncate();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testTruncate()
    {
        $s = Sanitize::truncate();
        $this->assertSame('Hell...', $s->sanitize('Hello world!'));
        $this->assertSame('Прив...', $s->sanitize('Привет мир!'));
    }
} 