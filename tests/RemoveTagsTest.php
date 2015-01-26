<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class RemoveTagsTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::removeTags();
        $this->assertSame(7, $s->sanitize(7));
    }
    public function testRemoveTags()
    {
        $s = Sanitize::removeTags();
        $this->assertSame('Hello world!foo', $s->sanitize('<b>Hello world!</b>foo'));
        $this->assertSame('foo', $s->sanitize('foo'));
    }
} 