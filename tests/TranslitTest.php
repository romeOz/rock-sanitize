<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class TranslitTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::translit();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testTranslit()
    {
        $s = Sanitize::translit();
        $this->assertSame('foo', $s->sanitize('foo'));
        $this->assertSame('AbV', $s->sanitize('АбВ'));
    }
} 