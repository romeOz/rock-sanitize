<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class UppercaseTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::uppercase();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testUppercase()
    {
        $s = Sanitize::uppercase();
        $this->assertSame('FOO', $s->sanitize('fOo'));
        $this->assertSame('АБГ', $s->sanitize('аБг'));
    }
} 