<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    public function test_()
    {
        $s = Sanitize::nested(false)->bool();
        $this->assertTrue($s->sanitize(['foo']));
        $this->assertTrue($s->sanitize(1));
        $this->assertTrue($s->sanitize('1'));
        $this->assertFalse($s->sanitize([]));
        $this->assertFalse($s->sanitize('0'));
        $this->assertFalse($s->sanitize(0));
    }
} 