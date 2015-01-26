<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class Foo
{
    public function lower($input)
    {
        return strtolower($input);
    }
}

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
            function($input){
                return strtoupper($input);
            }
        );
        $this->assertSame('FOO', $s->sanitize('foo'));
    }

    public function testCallMethod()
    {
        $s = Sanitize::call([new Foo, 'lower']);
        $this->assertSame('foo', $s->sanitize('FOO'));
    }

    /**
     * @expectedException \rock\sanitize\SanitizeException
     */
    public function testThrowException()
    {
        $s = Sanitize::call(7);
        $s->sanitize('foo');
    }
} 