<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class DecodeTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::decode();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function test_()
    {
        $s = Sanitize::decode();
        $this->assertSame('<b>foo</b> bar', $s->sanitize(htmlspecialchars('<b>foo</b> bar')));
    }
} 