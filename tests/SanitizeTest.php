<?php

namespace rockunit\sanitize;


use rock\sanitize\rules\Rule;
use rock\sanitize\Sanitize;

class Round extends Rule
{
    protected $precision = 0;
    public function __construct($precision = 0)
    {
        $this->precision= $precision;
    }

    /**
     * @inheritdoc
     */
    public function sanitize($input)
    {
        return round($input, $this->precision);
    }
}

class SanitizeTest extends \PHPUnit_Framework_TestCase
{
    public function testScalar()
    {
        $sanitize = Sanitize::removeTags();
        $this->assertSame('foo bar', $sanitize->sanitize('foo <b>bar</b>'));
    }

    public function testAttributes()
    {
        $input = [
            'name' => 'foo <b>bar</b>',
            'email' => 'bar <script>alert(\'Hello\');</script>baz'
        ];
        $expected = [
            'name' => 'foo bar',
            'email' => 'bar alert(\'Hello\');baz',
        ];
        $sanitize = Sanitize::attributes(
            [
                'name' => Sanitize::removeTags(),
                'email' => Sanitize::removeScript()
            ]
        );
        $this->assertSame($expected, $sanitize->sanitize($input));

        // skip
        $expected = [
            'name' => 'foo <b>bar</b>',
            'email' => 'bar alert(\'Hello\');baz',
        ];
        $sanitize = Sanitize::attributes(
            [
                'username' => Sanitize::removeTags(),
                'email' => Sanitize::removeScript()
            ]
        );
        $this->assertSame($expected, $sanitize->sanitize($input));
    }

    public function testAttributesAsObject()
    {
        $input = (object)[
            'name' => 'foo <b>bar</b>',
            'email' => 'bar <script>alert(\'Hello\');</script>baz'
        ];
        $expected = [
            'name' => 'foo bar',
            'email' => 'bar alert(\'Hello\');baz',
        ];
        $sanitize = Sanitize::attributes(
            [
                'name' => Sanitize::removeTags(),
                'email' => Sanitize::removeScript()
            ]
        );
        $this->assertInstanceOf('\stdClass', $sanitize->sanitize($input));
        $this->assertSame($expected, (array)$sanitize->sanitize($input));

        // skip
        $input = (object)[
            'name' => 'foo <b>bar</b>',
            'email' => 'bar <script>alert(\'Hello\');</script>baz'
        ];
        $expected = [
            'name' => 'foo <b>bar</b>',
            'email' => 'bar alert(\'Hello\');baz',
        ];
        $sanitize = Sanitize::attributes(
            [
                'username' => Sanitize::removeTags(),
                'email' => Sanitize::removeScript()
            ]
        );
        $this->assertInstanceOf('\stdClass', $sanitize->sanitize($input));
        $this->assertSame($expected, (array)$sanitize->sanitize($input));
    }

    /**
     * @expectedException \rock\sanitize\Exception
     */
    public function testAttributesThrowException()
    {
        $input = [
            'name' => 'foo <b>bar</b>',
            'email' => 'bar <script>alert(\'Hello\');</script>baz'
        ];
        $sanitize = Sanitize::attributes(
            [
                'name' => 'unknown',
                'email' => Sanitize::removeScript()
            ]
        );
        $sanitize->sanitize($input);
    }

    public function testRemainder()
    {
        $input = [
            'name' => 'foo <b>bar</b>',
            'email' => 'bar <script>alert(\'Hello\');</script>baz',
            'age' => 22,
            'wages' => -100
        ];
        $expected = [
            'name' => 'foo bar',
            'email' => 'bar alert(\'Hello\');baz',
            'age' => 22,
            'wages' => 0,
        ];
        $sanitize = Sanitize::attributes(
            [
                'name' => Sanitize::removeTags(),
                Sanitize::REMAINDER => Sanitize::positive(),
                'email' => Sanitize::removeScript(),
            ]
        );
        $this->assertSame($expected, $sanitize->sanitize($input));
    }

    public function testAllOf()
    {
        $input = [
            'name' => 'foo <b>bar</b>',
            'email' => 'bar <script>alert(\'Hello\');</script>baz'
        ];
        $expected = [
            'name' => 'foo bar',
            'email' => 'bar alert(\'Hello\');baz',
        ];
        $sanitize = Sanitize::allOf(Sanitize::removeTags());
        $this->assertSame($expected, $sanitize->sanitize($input));

        $sanitize = Sanitize::removeTags();
        $this->assertSame($expected, $sanitize->sanitize($input));
    }

    public function testAllOfAsObject()
    {
        $input = (object)[
            'name' => 'foo <b>bar</b>',
            'email' => 'bar <script>alert(\'Hello\');</script>baz'
        ];
        $expected = [
            'name' => 'foo bar',
            'email' => 'bar alert(\'Hello\');baz',
        ];
        $sanitize = Sanitize::allOf(Sanitize::removeTags());
        $this->assertInstanceOf('\stdClass', $sanitize->sanitize($input));
        $this->assertSame($expected, (array)$sanitize->sanitize($input));

        $sanitize = Sanitize::removeTags();
        $this->assertInstanceOf('\stdClass', $sanitize->sanitize($input));
        $this->assertSame($expected, (array)$sanitize->sanitize($input));
    }

    public function testNested()
    {
        $input = [
            'name' => '<b>Tom</b>',
            'other' => [
                'email' => '<b>tom@site.com</b>'
            ]
        ];

        $expected = [
            'name' => 'Tom',
            'other' =>
                [
                    'email' => 'tom@site.com',
                ],
        ];
        $sanitize = Sanitize::removeTags();
        $this->assertSame($expected, Sanitize::allOf($sanitize)->sanitize($input));

        $input = [
            [
                'name' => '<b>Tom</b>',
                'other' => [
                    'email' => '<b>tom@site.com</b>'
                ]
            ],
            [
                'name' => '<i>Jerry</i>',
                'other' => [
                    'email' => '<b>jerry@site.com</b>'
                ]
            ]
        ];

        $sanitize = Sanitize::removeTags();
        $expected = [
                [
                    'name' => 'Tom',
                    'other' =>
                        [
                            'email' => 'tom@site.com',
                        ],
                ],

                [
                    'name' => 'Jerry',
                    'other' =>
                        [
                            'email' => 'jerry@site.com',
                        ],
                ],
        ];
        $this->assertSame($expected, Sanitize::allOf($sanitize)->sanitize($input));
        $input = [
            'name' => '<b>Tom</b>',
            'other' => [
                'email' => '<b>tom@site.com</b>'
            ]
        ];

        $expected = [
            'name' => 'Tom',
            'other' =>
                [
                    'email' => '<b>tom@site.com</b>',
                ],
        ];
        $this->assertSame($expected, Sanitize::allOf($sanitize->nested(false))->sanitize($input));
    }

    /**
     * @expectedException \rock\sanitize\Exception
     */
    public function testUnknownRule()
    {
        Sanitize::unknown()->sanitize('');
    }

    public function testCustomRule()
    {
        $config = [
            'rules' => [
                'round' => Round::className()
            ]
        ];
        $s = new Sanitize($config);
        $this->assertSame(7.0, $s->round()->sanitize(7.4));
    }

    public function testRules()
    {
        $rules = ['removeTags', 'call' => ['trim'], 'toType'];
        $this->assertSame(777, Sanitize::rules($rules)->sanitize('<b> 777</b>'));
    }

    public function testExistsRule()
    {
        $this->assertTrue((new Sanitize())->existsRule('string'));
        $this->assertFalse((new Sanitize())->existsRule('unknown'));
    }

    public function testMultiRules()
    {
        $s = Sanitize::call('strip_tags');
        $this->assertSame(5, $s->sanitize('-5.5</b>     '));

        $s = Sanitize::call('floor')->toType()->call('abs');
        $this->assertSame(6.0, $s->sanitize('-5.5</b>     '));
    }

    /**
     * @link https://github.com/romeOz/rock-sanitize/issues/1
     */
    public function testIssue1()
    {
        $sanitize = Sanitize::removeTags()->call('trim')->toType();
        $input = [
            'form' => [
                '_csrf' => 'foo',
                'email' => '',
                'password' => ' <b>bar</b> ',
            ],
            'button' => '',
        ];
        $this->assertSame(
            [
                'form' =>
                    [
                        '_csrf' => 'foo',
                        'email' => '',
                        'password' => 'bar',
                    ],
                'button' => '',
            ],
            $sanitize->sanitize($input)
        );
    }

    /**
     * @link https://github.com/romeOz/rock-sanitize/issues/2
     */
    public function testIssue2()
    {
        $sanitize = Sanitize::rules([['call' => 'trim']]);
        $input = [
            'form' => [
                '_csrf' => 'foo',
                'email' => '',
                'password' => ' bar ',
            ],
            'button' => ' baz   ',
        ];
        $this->assertSame(
            array (
                'form' =>
                    array (
                        '_csrf' => 'foo',
                        'email' => '',
                        'password' => 'bar',
                    ),
                'button' => 'baz',
            ),
            $sanitize->sanitize($input)
        );
    }
} 