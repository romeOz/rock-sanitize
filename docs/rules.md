Rules
==================

### [General](#general-1)

 * [s::attributes()](#sattributesattribute_1--s1-attribute_2--s2-attribute_3--s3-)
 * [recursive](#recursive)
 * [remainder](#remainder)
  
### [Types](#types-1)

  * [s::bool()](#sbool)
  * [s::float()](#sfloat)
  * [s::float()](#sint)
  * [s::string()](#sstring)
  * [s::toType()](#stotype)
  
### [String](#string-1)

  * [s::basicTags()](#sbasictags)
  * [s::decode()](#sdecode)
  * [s::email()](#semail)
  * [s::encode()](#sencode)
  * [s::ip()](#sip)
  * [s::lowercase()](#slowercase)
  * [s::lowerFirst()](#slowerfirst)
  * [s::ltrimWords()](#sltrimwordswords)
  * [s::noiseWords()](#snoisewords)
  * [s::numbers()](#snumbers)
  * [s::removeScript()](#sremovescript)
  * [s::removeTags()](#sremovetags)
  * [s::replaceRandChars()](#sreplacerandchars)
  * [s::rtrimWords()](#srtrimwordswords)
  * [s::specialChars()](#sspecialchars)
  * [s::slug()](#sslug)
  * [s::trim()](#strim)
  * [s::truncate()](#struncate)
  * [s::truncateWords()](#struncatewords)
  * [s::uppercase()](#suppercase)
  * [s::upperFirst()](#supperfirst)
  
### [Numeric](#numeric-1)
  
  * [s::abs()](#sabs)
  * [s::negative()](#snegative)
  * [s::positive()](#spositive)
  * [s::round()](#sround)

### [Other](#other-1)

 * [v::call()](#scallcall)
 * [v::unserialize()](#sunserialize)

### [Custom rules](custom-rules.md)


### General

#### s::attributes(['attribute_1' => $s1, 'attribute_2' => $s2, 'attribute_3' => $s3,... ])
#### s::attributes(Sanitize $s)

For arrays or objects. Will sanitize each attribute individually.

```php
use rock\sanitize\Sanitize as s;

$input = [
    'name' => '<b>Tom</b>',
    'age' => -22
];

$attributes = [
    'name' => Sanitize::removeTags(),
    'age' => Sanitize::abs()
];
        
Sanitize::attributes($attributes)->sanitize($input);
/*
output:

[
  'name' => 'Tom',
  'age' => 22
]
*/
```

Sanitize all attributes:

```php
use rock\sanitize\Sanitize as s;

$input = [
    'name' => '<b>Tom</b>',
    'email' => '<i>tom@site.com</i>'
];

s::attributes(s::removeTags())->sanitize($input);
/*
output:

[
  'name' => 'Tom',
  'email' => 'tom@site.com',
]
*/
```
####recursive
	
Recursive sanitize array. `true` by default.

```php
use rock\sanitize\Sanitize as s;

$input = [
    'name' => '<b>Tom</b>',
    'other' => [
        'email' => '<b>tom@site.com</b>'
    ]
];

$sanitize = s::removeTags();
s::attributes($sanitize)->sanitize($input);

/*
output:

[
    'name' => 'Tom',
    'other' =>
        [
            'email' => 'tom@site.com',
        ],
]
*/

// recursive to false

s::recursive(false)->attributes(s::removeTags())->sanitize($input);

/*
output:

[
    'name' => 'Tom',
    'other' =>
        [
            'email' => '<b>tom@site.com</b>',
        ],
]
*/
```

####remainder

Default label `*`.

```php
use rock\sanitize\Sanitize as s;

$input = [
    'name' => 'Tom <b>Sawyer</b>',
    'email' => '<script>alert("Hello");</script>',
    'age' => 22,
    'wages' => -100
];

$sanitize = s::attributes(
    [
        'name' => Sanitize::removeTags(),
        'email' => Sanitize::removeScript(),
        '*' => Sanitize::positive(),
    ]
);

/*
output:

[
    'name' => 'Tom Sawyer',
    'email' => 'alert("Hello");',
    'age' => 22,
    'wages' => 0,
]
*/
```

Change default label:

```php
$sanitize = s::attributes([
    'name' => Sanitize::removeTags(),
    'email' => Sanitize::removeScript(),
    '_remainder' => Sanitize::positive(),
]);
$sanitize->setRemainder('_remainder');
```

### Types

#### s::bool()

Convert to boolean type.

```php
s::bool()->sanitize('foo');
// output: true

s::bool()->sanitize('');
// output: false
```

#### s::float()

Convert to float type.

```php
s::float()->sanitize('foo');
// output: 0.0

s::float()->sanitize('7');
// output: 7.0
```

#### s::int()

Convert to integer type.

```php
s::int()->sanitize('foo');
// output: 0

s::int()->sanitize('7');
// output: 7
```

#### s::string()

Convert to string type.

```php
s::string()->sanitize(7);
// output: '7'
```

#### s::toType()

Convert to type.

```php
s::toType()->sanitize('7');
// output: 7

s::toType()->sanitize('false');
// output: false
```

### String

#### s::basicTags()
#### s::basicTags(string $allowedTags)

Removes denied tags. 

> Allowed inline tags by default (`<b>`, `<h1>`, `<a>`,...). [See](https://github.com/romeOz/rock-sanitize/blob/master/src/rules/BasicTags.php#L7) 

```php
s::basicTags()->sanitize('<article>foo</article>');
// output: foo
```

#### s::decode()

```php
s::decode()->sanitize('Tom&amp;Jerry');
// output: Tom&Jerry
```

#### s::default()
#### s::default(mixed $default)

```php
s::defaultValue('foo')sanitize('');
// output: foo
```

#### s::email()

```php
s::email()->sanitize('<tom@site.com>');
// output: tom@site.com
```

#### s::encode()
#### s::encode(bool $doubleEncode = true)

```php
s::encode()->sanitize('Tom & Jerry');
// output: Tom &amp; Jerry
```

#### s::ip()
#### s::ip(bool $normalize = true)

Normalize IPv4/IPv6 and CIDR to range.

```php
s::ip()->sanitize('73.35.143.32/27');
s::ip()->sanitize('73.35.143.32/255.255.255.224');
// output: 73.35.143.32-73.35.143.63

s::ip()->sanitize('192.*.*.*');
// output: 192.0.0.0-192.255.255.255

s::ip()->sanitize('2001:db8:abc:1400::/54');
// output: 2001:db8:abc:1400::-2001:db8:abc:17ff:ffff:ffff:ffff:ffff
```

#### s::lowercase()

```php
s::lowercase()->sanitize('Foo');
// output: foo
```

#### s::lowerFirst()

```php
s::lowerFirst()->sanitize('FOO');
// output: fOO
```

#### s::ltrimWords($words)

```php
s::ltrimWords(['foo', 'bar'])->sanitize('foo text');
// output: text
```

#### s::noiseWords()
#### s::noiseWords($words)

Removes noise words. [See](https://github.com/romeOz/rock-sanitize/blob/master/src/rules/NoiseWords.php#L8).

```php
s::noiseWords()->sanitize('made by France');
// output: made France
```

#### s::numbers()

```php
s::numbers()->sanitize('special4you');
// output: '4'
```

#### s::removeScript()

Removes only tag `<script>`.

```php
s::removeScript()->sanitize('<script>alert("Hello world!");</script>foo');
// output: alert("Hello world!");foo
```

#### s::removeTags()

Removes all tags.

```php
s::removeTags()->sanitize('<b>Hello world!</b>');
// output: Hello world!
```

#### s::replaceRandChars()
#### s::replaceRandChars($replaceTo = '*')

For safe display.

```php
s::replaceRandChars()->sanitize('tom@site.com');
// output: tom**i***co*
```

#### s::rtrimWords($words)

```php
s::rtrimWords(['foo', 'bar'])->sanitize('text bar');
// output: text
```

#### s::specialChars()

Removes special chars (`«`, `»`, `.`, `,`,...). [See](http://en.wikipedia.org/wiki/Special_characters)

```php
s::specialChars()->sanitize('«Hello world!»');
// output: Hello world!
```

#### s::slug()
#### s::slug(string $replacement = '-', bool $lowercase = true)

Convert to slug. [See](https://en.wikipedia.org/wiki/Translit).

> language support:  Latin, Greek, Turkish, Russian, Ukrainian, Czech, Polish, Latvian, Vietnamese

```php
s::translit()->sanitize('Любовь');
// output: Lyubov

s::slug('-', false)->sanitize('Привет Мир');
// output: Privet-Mir
```

#### s::trim()

```php
s::trim()->sanitize(' foo   ');
// output: foo
```

#### s::truncate()
#### s::truncate(int $length = 4, string $suffix = '...')

```php
s::truncate()->sanitize('Hello world!');
// output: Hell...
```

#### s::truncateWords()
#### s::truncateWords(int $length = 100, string $suffix = '...')

```php
s::truncate(7)->sanitize('Hello world!');
// output: Hello
```

#### s::uppercase()

```php
s::uppercase()->sanitize('foo');
// output: FOO
```

#### s::upperFirst()

```php
s::upperFirst()->sanitize('foo');
// output: Foo
```

### Numeric

#### s::abs()

Convert to absolute.

```php
s::abs()->sanitize('-7.7');
// output: 7.7
```

#### s::negative()

If the value is positive, it is converted to 0.

```php
s::negative()->sanitize(7);
// output: 0

s::negative()->sanitize(-7);
// output: -7
```

#### s::positive()

If the value is negative, it is converted to 0.

```php
s::positive()->sanitize(-7);
// output: 0

s::positive()->sanitize(7);
// output: 7
```

#### s::round()
#### s::round(int $precision = 0)

Rounds a float.

```php
s::round()->sanitize('7.7');
// output: 8.0
```

### Other

#### s::call($call)
#### s::call($call, array $args)

A `$call` can be an callback (`function(){}`), string (`mb_strtolower`) and array (`[$this, 'method']`). 

```php
s::call('mb_strtolower', ['UTF-8'])->sanitize('АбВ');
// output: абв

$callback =  function($input){
    return strtoupper($input);
};
s::call($callback)->sanitize('foo');
// output: FOO
```

#### s::unserialize()
 	
```php
$input = '{"name" : "<b>Tom</b>", "email" : "<i>tom@site.com</i>"}';
s::removeTags()->unserialize()->sanitize($input);
/*
output:
 
[
    'name' => 'Tom',
    'email' => 'tom@site.com',
]
*/

$input = [
    'name' => '<b>Tom</b>',
    'other' => '{"email" : "<i>tom@site.com</i>"}'
];
s::removeTags()->unserialize()->sanitize($input);
/*
output:
 
[
  'name' => 'Tom',
  'other' => 
  [
    'email' => 'tom@site.com',
  ]
]
*/
```