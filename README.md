Sanitizator for PHP
=======================

[![Latest Stable Version](https://poser.pugx.org/romeOz/rock-sanitize/v/stable.svg)](https://packagist.org/packages/romeOz/rock-sanitize)
[![Total Downloads](https://poser.pugx.org/romeOz/rock-sanitize/downloads.svg)](https://packagist.org/packages/romeOz/rock-sanitize)
[![Build Status](https://travis-ci.org/romeOz/rock-sanitize.svg?branch=master)](https://travis-ci.org/romeOz/rock-sanitize)
[![HHVM Status](http://hhvm.h4cc.de/badge/romeoz/rock-sanitize.svg)](http://hhvm.h4cc.de/package/romeoz/rock-sanitize)
[![Coverage Status](https://coveralls.io/repos/romeOz/rock-sanitize/badge.svg?branch=master)](https://coveralls.io/r/romeOz/rock-sanitize?branch=master)
[![License](https://poser.pugx.org/romeOz/rock-sanitize/license.svg)](https://packagist.org/packages/romeOz/rock-sanitize)

[Rock sanitizator on Packagist](https://packagist.org/packages/romeOz/rock-sanitize)

Features
-------------------

 * Sanitization of scalar variable and array (`attributes()`)
 * Customization of sanitization rules
 * Module for [Rock Framework](https://github.com/romeOz/rock)
 
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
Sanitize::attributes(Sanitize::removeTags())->sanitize($input);

// or
Sanitize::removeTags()->sanitize($input);

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

Demo & Tests (one of two ways)
-------------------

####1. Docker + Ansible

 * [Install Docker](https://docs.docker.com/installation/) or [askubuntu](http://askubuntu.com/a/473720)
 * `docker run -d -p 8080:80 romeoz/vagrant-rock-sanitize`
 * Open demo [http://localhost:8080/](http://localhost:8080/)
 
####2. VirtualBox + Vagrant + Ansible

 * `git clone https://github.com/romeOz/vagrant-rock-sanitize.git`
 * [Install VirtualBox](https://www.virtualbox.org/wiki/Downloads)
 * [Install Vagrant](https://www.vagrantup.com/downloads), and additional Vagrant plugins `vagrant plugin install vagrant-hostsupdater vagrant-vbguest vagrant-cachier`
 * `vagrant up`
 * Open demo [http://www.rock-sanitize/](http://www.rock-sanitize/) or [http://192.168.33.36/](http://192.168.33.36/)

> Work/editing the project can be done via ssh:

```bash
vagrant ssh
cd /var/www/rock-sanitize
```

Requirements
-------------------

 * **PHP 5.4+**

License
-------------------

The Rock Sanitizator is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).