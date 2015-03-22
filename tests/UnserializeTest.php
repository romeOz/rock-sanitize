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
        $input = [
            'foo <b>bar</b>',
            Json::encode(['msg' => '<b>bar</b> baz'])
        ];
        $expected = ['foo bar', ['msg' => 'bar baz',],];

        $sanitize = Sanitize::attributes(Sanitize::removeTags()->unserialize());
        $this->assertSame($expected,$sanitize->sanitize($input));

        $sanitize = Sanitize::attributes(['*' => Sanitize::removeTags()->unserialize()]);
        $this->assertSame($expected,$sanitize->sanitize($input));

        $sanitize = Sanitize::attributes([0 => Sanitize::positive(), '*' => Sanitize::removeTags()->unserialize()]);
        $expected = [0,  ['msg' => 'bar baz',],];
        $this->assertSame($expected, $sanitize->sanitize($input));

        $input = [
            'foo' => ' <b>foo</b>     ',
            'bar' => '    <b>bar   </b>',
            'baz' => '{"baz" : " <b> baz  </b>     "}'
        ];

        $s = Sanitize::attributes(
            [
                'bar' => Sanitize::removeTags()->trim(),
                'baz' => Sanitize::unserialize()->removeTags()->trim(),
            ]
        );

        $expected = [
            'foo' => ' <b>foo</b>     ',
            'bar' => 'bar',
            'baz' =>
                [
                    'baz' => 'baz',
                ],
        ];
        $this->assertSame($expected, $s->sanitize($input));
    }

    public function testNested()
    {
        $input = [
            'text_1' => 'foo <b>bar</b>',
            'text_2' => Json::encode(['msg' => '<b>bar</b> baz']),
            'text_3' => Json::encode(['msg' => '<b>foo</b>']),

        ];
        $sanitize = Sanitize::attributes(['*' => Sanitize::removeTags()->unserialize(), 'text_2' => Sanitize::recursive(false), ]);
        $expected = [
            'text_1' => 'foo bar',
            'text_2' => '{"msg":"<b>bar<\\/b> baz"}',
            'text_3' =>
                [
                    'msg' => 'foo',
                ],
        ];
        $this->assertSame($expected, $sanitize->sanitize($input));
    }
} 