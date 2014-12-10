<?php

/*
 * You may use require_once similar to below to autoload
 * the MyAllocator PHP SDK. Composer package install is
 * preferred.
 */

require_once(dirname(__FILE__) . '/MyAllocator.php');

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
