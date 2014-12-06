#myallocator-php

The PHP SDK for MyAllocator integration (JSON & XML).

## Requirements

PHP 5.3 and later.

## Composer

You can install via composer. Add the following to your project's +composer.json+.

    {
        "require": {
            "Myallocator/phpsdk": "1.*"
        }
    }

Then install via:

    composer.phar install

To use the bindings, either user Composer's autoload[https://getcomposer.org/doc/00-intro.md#autoloading]:

    require_once('vendor/autoload.php');

Or manually:

    require_once('/path/to/vendor/MyAllocator/myallocator-sdk-php/src/MyAllocator.php');

## Manual Installation

Grab the latest version of the SDK:

    git clone https://github.com/MyAllocator/myallocator-php.git

To use the bindings, add the following to a PHP script:

    require_once('/path/to/myallocator-sdk-php/src/MyAllocator.php');

## Getting Started

A simple usage example:

TODO

## Documentation

Please see TODO for up-to-date documentation.

## Tests

You can run phpunit tests from the top directory:

    vendor/bin/phpunit --debug tests
    vendor/bin/phpunit --debug tests/json
    vendor/bin/phpunit --debug tests/xml

Note, there is a different set of tests for json and XML.
The json tests use the 'array' dataFormat (refer to *src/MyAllocator/Config/Config.php*)
