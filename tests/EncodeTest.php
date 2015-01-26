<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class EncodeTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::encode();
        $this->assertSame(7, $s->sanitize(7));

        $s = Sanitize::string()->encode();
        $this->assertSame('7', $s->sanitize(7));
    }
    public function test_()
    {
        $s = Sanitize::encode();
        $this->assertSame(htmlspecialchars('<b>foo</b> bar'), $s->sanitize('<b>foo</b> bar'));
    }
} 