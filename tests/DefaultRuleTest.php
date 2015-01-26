<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class DefaultRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $s = Sanitize::defaultValue('foo');
        $this->assertSame('foo', $s->sanitize(''));
        $this->assertSame('bar', $s->sanitize('bar'));
    }
} 