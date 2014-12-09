#myallocator-php

MyAllocator PHP SDK (JSON & XML). Property management systems (PMS) can use this SDK to quickly and reliably integrate with the MyAllocator API to enable distribution for their customers.

MyAllocator [https://www.myallocator.com/]

MyAllocator API Documentation [http://myallocator.github.io/apidocs/]

## Requirements

PHP 5.3.2 and later.

## Composer

You can install via composer. Add the following to your project's `composer.json`.

    {
        "require": {
            "Myallocator/phpsdk": "1.*"
        }
    }

Then install via:

    composer.phar install

To use the bindings, either use Composer's autoload [https://getcomposer.org/doc/00-intro.md#autoloading]:

    require_once('vendor/autoload.php');

Or manually:

    require_once('/path/to/vendor/MyAllocator/myallocator-sdk-php/src/MyAllocator.php');

## Manual Installation

Grab the latest version of the SDK:

    git clone https://github.com/MyAllocator/myallocator-php.git

To use the bindings, add the following to a PHP script:

    require_once('/path/to/myallocator-sdk-php/src/MyAllocator.php');

## Documentation

Please see TODO for up-to-date documentation.

## Getting Started

A simple usage example (`src/example_autoload.php`):

    require_once(dirname(__FILE__) . '/myallocator-sdk-php/src/MyAllocator.php');
    use MyAllocator\phpsdk\src\Api\HelloWorld;

    $params = array(
        'Auth' => 'true',
        'hello' => 'world'
    );

    $api = new HelloWorld();
    $api->setConfig('dataFormat', 'array');
    $rsp = $api->callApiWithParams($params);
    var_dump($rsp);

The require_once is not required if autoloaded via composer.

The setConfig is not required once `src/MyAllocator/Config/Config.php` has been configured.

### Parameter Validation

The SDK supports parameter validation for array and json data formats, which can be configured via the `paramValidationEnabled` configuration in `src/MyAllocator/Config/Config.php`. If you prefer to send a raw request for performance, or other reasons, set this configuration to false. If parameter validation is enabled:

1.  Required and optional Api keys are defined via $keys array in each Api class.
2.  Top level required and optional keys are validated prior to sending a request to MyAllocator.
3.  An ApiException is thrown if a required key is not present.
4.  Top level keys not defined in $keys are stripped from parameters.
5.  Minimum optional parameters are enforced.

### Data Formats

The SDK supports three data in/out formats (array, json, xml), which can be configured via the `dataFormat` configuration in `src/MyAllocator/Config/Config.php`. The following table illustrates the formats used for the request flow based on dataFormat.

    you->SDK(dataFormat)    SDK->MA     MA->SDK     SDK->you
    --------------------    -------     -------     --------
    array                   json        json        array
    json                    json        json        json
    xml                     xml         xml         xml

`array` and `json` data formats are preferred vs. `xml`.

Note, parameter validation only supports array and json data formats. For json data validation, the data must be decoded and re-encoded after validation. For xml data, the raw request is sent to MyAllocator and raw response returned to you. Disable `paramValidationEnabled` in Config.php to skip parameter validation.

### API Response Format

A request call will always return an array with the following response structure:

    return array(
        'code' => $rcode,
        'headers' => $headers,
        'response' => $resp
    );

`code` is the HTTP response code.

`headers` is the response headers (only returned if dataFormat = xml)

`response` is the response payload in the configured dataFormat.

### Setup Local Environment Variables

Most of the test cases use local environment variables and will be skipped if not provided. Export the following local environment variables from your data to use with the related test cases:

    myallocator-sdk-php$ cat test/ENVIRONMENT_CREDENTIALS 
    #!/bin/bash
    export ma_vendorId=xxxxx
    export ma_vendorPassword=xxxxx
    export ma_userId=xxxxx
    export ma_userPassword=xxxxx
    export ma_userToken=xxxxx
    export ma_propertyId=xxxxx
    export ma_PMSUserId=xxxxx
    myallocator-sdk-php$ source test/ENVIRONMENT_CREDENTIALS

## Tests

You can run phpunit tests from the top directory:

    Run common infra, JSON API, and XML API test cases. This excludes some of the advanced API's. Refer to `phpunit.xml`.
    vendor/bin/phpunit --debug

    Run JSON API test cases.
    vendor/bin/phpunit --debug tests/json

    Run XML API test cases.
    vendor/bin/phpunit --debug tests/xml

    Run common infra test cases.
    vendor/bin/phpunit --debug tests/common

Note, there is a different set of tests for json and XML.

The json tests use the `array` dataFormat to interface with the SDK. Refer to `src/MyAllocator/Config/Config.php`.

### Troubleshooting

Set `debugsEnabled` to true in `src/MyAllocator/Config/Config.php` to display request and response data in the SDK interface and API transfer data formats for an API request.
