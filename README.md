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

## Documentation

Please see TODO for up-to-date documentation.

## Getting Started

A simple usage example:

TODO

### Parameter Validation

The SDK supports parameter validation for array and json data formats, which can be configured via the *paramValidationEnabled* configuration in *src/MyAllocator/Config/Config.php*. If you prefer to send a raw request for performance, or other reasons, set this configuration to false.

When enabled:
1. Required and optional Api keys are defined via $keys array in each Api class.
2. Top level required and optional keys are validated prior to sending a request to MyAllocator.
3. If a required key is not present, an ApiException is thrown.
4. If a top level key that is not defined in $keys is present in parameters, it is removed. 
5. Minimum optional parameters is enforced.

Note, parameter validation is not supported with xml. The raw request is sent and response returned.

### Data Formats

The SDK supports three data in/out formats (array, json, xml), which can be configured via the *dataFormat* configuration in *src/MyAllocator/Config/Config.php*. The following table illustrates the formats used for the request flow based on dataFormat.

    you->SDK(dataFormat)    SDK->MA     MA->SDK     SDK->you
    --------------------    -------     -------     --------
    array                   json        json        array
    json                    json        json        json
    xml                     xml         xml         xml

Note, parameter validation only supports array and json data formats. For json data validation, the data must be decoded and re-encoded after validation. If you do not wish to experience the cost, disable 'paramValidationEnabled' above. For xml data, the raw request is sent to MyAllocator and raw response returned to you.

### API Response Format

A request call will always return an array with the following response structure:

    return array(
        'code' => $rcode,
        'headers' => $headers,
        'response' => $resp
    );

*code* is the HTTP response code.
*headers* is the reponse hears (only returned if dataFormat = xml)
*response* is the response payload in the configured dataFormat.

## Tests

You can run phpunit tests from the top directory:

    vendor/bin/phpunit --debug tests
    vendor/bin/phpunit --debug tests/json
    vendor/bin/phpunit --debug tests/xml

Note, there is a different set of tests for json and XML.
The json tests use the 'array' dataFormat (refer to *src/MyAllocator/Config/Config.php*)

### Setup Local Environment Variables

Most of the test cases use local environment variables and will be skipped if they are not provided. Export the following local environment variables from your data to use with the related test cases:

    myallocator-sdk-php$ cat test/ENVIRONMENT_CREDENTIALS 
    #!/bin/bash
    export ma_vendorId=phpsdk
    export ma_vendorPassword=xxxxx
    export ma_userId=phpsdkuser
    export ma_userPassword=xxxxx
    export ma_userToken=xxxxx
    export ma_propertyId=xxxxx
    export ma_PMSUserId=xxxxx

    myallocator-sdk-php$ source test/ENVIRONMENT_CREDENTIALS
