<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class UpperFirstTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::upperFirst();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testUpperFirst()
    {
        $s = Sanitize::upperFirst();
        $this->assertSame('Foo', $s->sanitize('foo'));
        $this->assertSame('Абг', $s->sanitize('абг'));
    }
} 