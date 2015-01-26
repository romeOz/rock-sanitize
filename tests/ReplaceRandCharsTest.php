<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class ReplaceRandCharsTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::replaceRandChars();
        $this->assertSame(7, $s->sanitize(7));
    }
    public function testReplaceRandChars()
    {
        $s = Sanitize::replaceRandChars();
        $this->assertContains('*', $s->sanitize('Hello world!'));
    }
} 