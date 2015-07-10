<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class CallTest extends \PHPUnit_Framework_TestCase
{
    public function testCallFunction()
    {
        $s = Sanitize::call('mb_strtolower', ['UTF-8']);
        $this->assertSame('абв', $s->sanitize('АбВ'));
    }

    public function testCallback()
    {
        $s = Sanitize::call(
            function ($input) {
                return strtoupper($input);
            }
        );
        $this->assertSame('FOO', $s->sanitize('foo'));
    }

    public function testCallMethod()
    {
        $s = Sanitize::call([new CallClass, 'lower']);
        $this->assertSame('foo', $s->sanitize('FOO'));
    }
}


class CallClass
{
    public function lower($input)
    {
        return strtolower($input);
    }
}