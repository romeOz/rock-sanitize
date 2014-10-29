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

####For Array or Object

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

 * [Rules](https://github.com/romeOz/rock-sanitize/blob/master/docs/rules.md)
 * [Custom rules](https://github.com/romeOz/rock-sanitize/blob/master/docs/custom-rules.md)

Demo & Tests
-------------------

Use a specially prepared environment (Vagrant + Ansible).

###Out of the box:

 * Ubuntu 14.04 64 bit

> If you need to use 32 bit of Ubuntu, then uncomment `config.vm.box_url` the appropriate version in the file `/path/to/Vagrantfile`.

 * Nginx 1.6
 * PHP-FPM 5.6
 * Composer
 * Local IP loop on Host machine /etc/hosts and Virtual hosts in Nginx already set up!

###Installation:

1. [Install Composer](https://getcomposer.org/doc/00-intro.md#globally)
2. ```composer create-project --prefer-dist romeoz/rock-sanitize```
3. [Install Vagrant](https://www.vagrantup.com/downloads), and additional Vagrant plugins ```vagrant plugin install vagrant-hostsupdater vagrant-vbguest vagrant-cachier```
4. ```vagrant up```
5. Open demo [http://rock.sanitize/](http://rock.sanitize/) or [http://192.168.33.36/](http://192.168.33.36/)

> Work/editing the project can be done via ssh:
```bash
vagrant ssh
cd /var/www/
```


Requirements
-------------------

 * **PHP 5.4+**

License
-------------------

The Rock Sanitizator is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).