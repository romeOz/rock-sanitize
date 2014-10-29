Rules
==================

### [Generics](#generics-1)

 * [s::attributes](#sattributesattribute_1--s1-attribute_2--s2-attribute_3--s3-)
 * [s::allOf()](#sallofs)
  
### [Types](#types-1)
  
### [String](#string-1)
  
### [Numeric](#numeric-1)

### [Other](#other-1)

 * [v::call()](#scallmixed-callback)

### [Custom rules](custom-rules.md)


### Generics

#### s::attributes(['attribute_1' => $s1, 'attribute_2' => $s2, 'attribute_3' => $s3,... ])

For arrays or objects. Will sanitize each attribute individually.

```php
use rock\sanitize\Sanitize as s;

$input = [
    'name' => '<b>Tom</b>',
    'email' => '<i>(tom@site.com)</i>'
];

$attributes = [
    'name' => s::removeTags(),
    'email' => s::removeTags()->email()
];
        
s::attributes($attributes)->sanitize($input);
/*
output:

[
  'name' => 'Tom',
  'email' => 'tom@site.com',
]
*/
```

#### s::allOf($s)

For arrays or objects. Will sanitize all attributes.

```php
use rock\sanitize\Sanitize as s;

$input = [
    'name' => '<b>Tom</b>',
    'email' => '<i>(tom@site.com)</i>'
];

s::allOf(s::removeTags())->sanitize($input);
/*
output:

[
  'name' => 'Tom',
  'email' => '(tom@site.com)',
]
*/
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

> Allowed inline tags by default (`<b>`, `<h1>`, `<a>`,... [see](https://github.com/romeOz/rock-sanitize/blob/master/src/rules/BasicTags.php#L7) ).

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
#### s::encode($doubleEncode = true)

```php
s::encode()->sanitize('Tom & Jerry');
// output: Tom &amp; Jerry
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

#### s::noiseWords($words)

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

Removes special chars (`«`, `»`, `.`, `,`,... [see](http://en.wikipedia.org/wiki/Special_characters)).

```php
s::specialChars()->sanitize('«Hello world!»');
// output: Hello world!
```

#### s::translit()

Convert to translit. [See](https://en.wikipedia.org/wiki/Translit).

> Only supported Cyrillic.

```php
s::translit()->sanitize('Любовь');
// output: Lyubov'
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

#### s::negative()

```php
s::negative()->sanitize(7);
// output: 0

s::negative()->sanitize(-7);
// output: -7
```

#### s::positive()

```php
s::positive()->sanitize(-7);
// output: 0

s::positive()->sanitize(7);
// output: 7
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