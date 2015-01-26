<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class RemoveScriptTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::removeScript();
        $this->assertSame(7, $s->sanitize(7));
    }
    public function testRemoveScript()
    {
        $s = Sanitize::removeScript();
        $this->assertSame('alert("Hello world!");foo', $s->sanitize('<script>alert("Hello world!");</script>foo'));
        $this->assertSame('foo', $s->sanitize('foo'));
    }
} 