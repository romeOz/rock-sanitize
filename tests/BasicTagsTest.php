<?php

namespace rockunit\sanitize;


use rock\sanitize\Sanitize;

class BasicTagsTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::basicTags();
        $this->assertSame(['foo'], $s->sanitize(['foo']));
    }

    public function testSuccess()
    {
        $s = Sanitize::basicTags();
        $this->assertSame('foo bar', $s->sanitize('<article>foo</article> bar'));
    }

    public function testFail()
    {
        $s = Sanitize::basicTags();
        $this->assertSame('<b>foo</b> bar', $s->sanitize('<b>foo</b> bar'));
    }

    public function testCustomAllowedTags()
    {
        $s = Sanitize::basicTags('<article>');
        $this->assertSame('<article>foo</article> bar', $s->sanitize('<article>foo</article> bar'));
        $this->assertSame('foo bar', $s->sanitize('<b>foo</b> bar'));
    }
} 