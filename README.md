# Utopia Preloader

[![Build Status](https://travis-ci.org/utopia-php/preloader.svg?branch=master)](https://travis-ci.org/utopia-php/preloader)
[![Discord](https://badgen.net/badge/discord/chat/green)](https://discord.gg/GSeTUeA)
![Total Downloads](https://img.shields.io/packagist/dt/utopia-php/preloader.svg)

Utopia Preloader library is simple and lite library for managing PHP preloading configuration. This library is aiming to be as simple and easy to learn and use.

Although this library is part of the [Utopia Framework](https://github.com/utopia-php/framework) project it is dependency free and can be used as standalone with any other PHP project or framework.

## Getting Started

Install using composer:
```bash
composer require utopia-php/preloader
```

```php
<?php

if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
}

use Utopia\Preloader;

(new Preloader())
    ->paths(realpath(__DIR__ . '/../app/config'))
    ->paths(realpath(__DIR__ . '/../src'))
    ->ignore(realpath(__DIR__ . '/../vendor/twig/twig'))
    ->ignore(realpath(__DIR__ . '/../vendor/guzzlehttp/guzzle'))
    ->ignore(realpath(__DIR__ . '/../vendor/geoip2'))
    ->ignore(realpath(__DIR__ . '/../vendor/maxmind'))
    ->ignore(realpath(__DIR__ . '/../vendor/maxmind-db'))
    ->ignore(realpath(__DIR__ . '/../vendor/piwik'))
    ->load();

```

## System Requirements

Utopia Framework requires PHP 7.1 or later. We recommend using the latest PHP version whenever possible.

## Authors

**Eldad Fux**

+ [https://twitter.com/eldadfux](https://twitter.com/eldadfux)
+ [https://github.com/eldadfux](https://github.com/eldadfux)

## Copyright and license

The MIT License (MIT) [http://www.opensource.org/licenses/mit-license.php](http://www.opensource.org/licenses/mit-license.php)