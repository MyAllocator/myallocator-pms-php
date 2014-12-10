#myallocator-php-sdk

MyAllocator PHP SDK (JSON & XML). Property management systems (PMS) can use this SDK to quickly and reliably integrate with the MyAllocator API to enable distribution for their customers.

MyAllocator PHP SDK Documentation [http://myallocator.github.io/myallocator-php-sdk-docs/]

MyAllocator API Documentation [http://myallocator.github.io/apidocs/]

MyAllocator [https://www.myallocator.com/]

## Requirements

PHP 5.3.2 and later.

## Documentation

Please see http://myallocator.github.io/myallocator-php-sdk-docs/ for the complete and up-to-date SDK documentation.

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
    try {
        $rsp = $api->callApiWithParams($params);
    } catch (Exception $e) {
        $rsp = 'Oops: '.$e->getMessage();
    }
    var_dump($rsp);

The require_once is not required if autoloaded via composer.

The setConfig is not required once `src/MyAllocator/Config/Config.php` has been configured.

## Configuration

The default configuration file can be found at at `src/MyAllocator/Config/Config.php`. The following is configurable:

### `paramValidationEnabled`

The SDK supports parameter validation for array and json data formats, which can be configured via the `paramValidationEnabled` configuration in `src/MyAllocator/Config/Config.php`. If you prefer to send a raw request for performance, or other reasons, set this configuration to false. If parameter validation is enabled:

1.  Required and optional Api keys are defined via $keys array in each Api class.
2.  Top level required and optional keys are validated prior to sending a request to MyAllocator.
3.  An ApiException is thrown if a required key is not present.
4.  Top level keys not defined in $keys are stripped from parameters.
5.  Minimum optional parameters are enforced.

### `dataFormat`

The SDK supports three data in/out formats (array, json, xml), which can be configured via the `dataFormat` configuration in `src/MyAllocator/Config/Config.php`. The following table illustrates the formats used for the request flow based on dataFormat.

    you->SDK(dataFormat)    SDK->MA     MA->SDK     SDK->you
    --------------------    -------     -------     --------
    array                   json        json        array
    json                    json        json        json
    xml                     xml         xml         xml

`array` and `json` data formats are preferred vs. `xml`.

Note, parameter validation only supports array and json data formats. For json data validation, the data must be decoded and re-encoded after validation. For xml data, the raw request is sent to MyAllocator and raw response returned to you. Disable `paramValidationEnabled` in Config.php to skip parameter validation.

### `debugsEnabled`

Set `debugsEnabled` to true in `src/MyAllocator/Config/Config.php` to display request and response data in the SDK interface and API transfer data formats for an API request.

## API Response Format

A successful request call will always return an array with the following response structure:

    return array(
        'code' => $rcode,
        'headers' => $headers,
        'response' => $resp
    );

`code` is the HTTP response code.

`headers` is the response headers (only returned if dataFormat = xml).

`response` is the response payload in the configured dataFormat.

Requests may also return any of the exceptions defined in `src/MyAllocator/Exception/`. Be sure to wrap your API calls in try blocks. You may use the `getHttpStatus`, `getHttpBody`, and `getJsonBody` methods defined in `/Exception/MaException.php` within an exception block for information.

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

#### Setup Local Environment Variables

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
