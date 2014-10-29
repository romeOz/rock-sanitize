Sanitizator for PHP
=======================

[![Latest Stable Version](https://poser.pugx.org/romeOz/rock-sanitize/v/stable.svg)](https://packagist.org/packages/romeOz/rock-sanitize)
[![Total Downloads](https://poser.pugx.org/romeOz/rock-sanitize/downloads.svg)](https://packagist.org/packages/romeOz/rock-sanitize)
[![Build Status](https://travis-ci.org/romeOz/rock-sanitize.svg?branch=master)](https://travis-ci.org/romeOz/rock-sanitize)
[![Coverage Status](https://coveralls.io/repos/romeOz/rock-sanitize/badge.png?branch=master)](https://coveralls.io/r/romeOz/rock-sanitize?branch=master)
[![License](https://poser.pugx.org/romeOz/rock-sanitize/license.svg)](https://packagist.org/packages/romeOz/rock-sanitize)

[Rock sanitizator on Packagist](https://packagist.org/packages/romeOz/rock-sanitize)

Features
-------------------

 * Sanitization of scalar variable and array (`attributes()`, `allOf()`)
 * Customization of sanitization rules
 
Installation
-------------------

From the Command Line:

```composer require romeoz/rock-sanitize:*```

In your composer.json:

```json
{
    "require": {
        "romeoz/rock-sanitize": "*"
    }
}
```

Quick Start
-------------------

```php
use rock\sanitize\Sanitize;

Sanitize::removeTags()
    ->lowercase()
    ->sanitize('<b>Hello World!</b>');
// output: hello world!    
```

####Attributes (Array or Object)
```php
use rock\sanitize\Sanitize;

$input = [
    'name' => '<b>Tom</b>',
    'email' => '<i>(tom@site.com)</i>'
];

$attributes = [
    'name' => Sanitize::removeTags(),
    'email' => Sanitize::removeTags()->email()
];
        
Sanitize::attributes($attributes)->sanitize($input);
/*
output:

[
  'name' => 'Tom',
  'email' => 'tom@site.com',
]
*/

// all attributes:
Sanitize::allOf(Sanitize::removeTags())->sanitize($input);
/*
output:

[
  'name' => 'Tom',
  'email' => '(tom@site.com)',
]
*/
```

Documentation
-------------------

**TODO**

Requirements
-------------------

 * **PHP 5.4+**

License
-------------------

The Rock Sanitizator is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).