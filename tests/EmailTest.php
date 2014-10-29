<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::email();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function test_()
    {
        $s = Sanitize::email();
        $this->assertSame('tom@site.com', $s->sanitize('(tom@site.com)'));
        $this->assertSame('foo', $s->sanitize('foo'));
        $this->assertSame(7, $s->sanitize(7));
    }
} 