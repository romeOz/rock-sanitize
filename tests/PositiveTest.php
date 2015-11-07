<?php

namespace rockunit;


use rock\sanitize\Sanitize;

class PositiveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerSuccess
     */
    public function testSuccess($input)
    {
        $s = Sanitize::positive()->setRecursive(false);
        $this->assertSame(0, $s->sanitize($input));
    }

    /**
     * @dataProvider providerFail
     */
    public function testFail($input, $expected)
    {
        $s = Sanitize::positive()->setRecursive(false);
        $this->assertSame($expected, $s->sanitize($input));
    }

    public function providerSuccess()
    {
        return [
            [-7],
            ['-7'],
            ['-7.5'],
            ['foo'],
            [[]],
            [['foo']],
        ];
    }

    public function providerFail()
    {
        return [
            [7, 7],
            ['7', 7],
            ['7.5', 7.5],
        ];
    }
} 