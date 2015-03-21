<?php

namespace rockunit;


use rock\helpers\Json;
use rock\sanitize\Sanitize;

class UnserializeTest extends \PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $s = Sanitize::unserialize();
        $this->assertSame(7, $s->sanitize(7));
    }

    public function testUnserialize()
    {
        $sanitize = Sanitize::removeTags()->unserialize();
        $input = [
            'foo <b>bar</b>',
            Json::encode(['msg' => '<b>bar</b> baz'])
        ];
        $expected = [
            0 => 'foo bar',
            1 =>
                [
                    'msg' => 'bar baz',
                ],
        ];
        $this->assertSame($expected,$sanitize->sanitize($input));

        $sanitize = Sanitize::attributes(Sanitize::removeTags()->unserialize());
        $this->assertSame($expected,$sanitize->sanitize($input));

        $sanitize = Sanitize::attributes([Sanitize::REMAINDER => Sanitize::removeTags()->unserialize()]);
        $this->assertSame($expected,$sanitize->sanitize($input));

        $sanitize = Sanitize::attributes([0 => Sanitize::positive(), Sanitize::REMAINDER => Sanitize::removeTags()->unserialize()]);
        $expected = array (
            0 => 0,
            1 =>
                array (
                    'msg' => 'bar baz',
                ),
        );
        $this->assertSame($expected, $sanitize->sanitize($input));
    }

    public function testNested()
    {
        $input = [
            'text_1' => 'foo <b>bar</b>',
            'text_2' => Json::encode(['msg' => '<b>bar</b> baz']),
            'text_3' => Json::encode(['msg' => '<b>foo</b>']),

        ];
        $sanitize = Sanitize::attributes([Sanitize::REMAINDER => Sanitize::removeTags()->unserialize(), 'text_2' => Sanitize::nested(false), ]);
        $expected = array (
            'text_1' => 'foo bar',
            'text_2' => '{"msg":"<b>bar<\\/b> baz"}',
            'text_3' =>
                array (
                    'msg' => 'foo',
                ),
        );
        $this->assertSame($expected, $sanitize->sanitize($input));
    }
} 