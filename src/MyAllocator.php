<?php
/**
 * Copyright (C) 2020 Digital Arbitrage, Inc
 *
 * A copy of the LICENSE can be found in the LICENSE file within
 * the root directory of this library.  
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */

/*
 * Tested on PHP 5.5.9
 *
 * Usage:
 * 
 * require_once(dirname(__FILE__) . '/MyAllocator.php');
 * use MyAllocator\phpsdk\src\Api\HelloWorld;
 *
 * $params = array(
 *     'Auth' => 'true',
 *     'hello' => 'world'
 * );
 *
 * $api = new HelloWorld();
 * $api->setConfig('dataFormat', 'array');
 * $rsp = $api->callApiWithParams($params);
 * try {
 *     $rsp = $api->callApiWithParams($params);
 * } catch (Exception $e) {
 *     $rsp = 'Oops: '.$e->getMessage();
 * }
 * var_dump($rsp);
 */

//Required packages
if (!function_exists('curl_init')) {
  throw new Exception('Myallocator needs the CURL PHP extension.');
}

if (!function_exists('json_decode')) {
  throw new Exception('Myallocator needs the JSON PHP extension.');
}

if (!function_exists('mb_detect_encoding')) {
  throw new Exception('Myallocator needs the Multibyte String PHP extension.');
}

// Initial Dependencies
require_once(dirname(__FILE__) . '/MyAllocator/MaBaseClass.php');
require_once(dirname(__FILE__) . '/MyAllocator/Exception/MaException.php');
require_once(dirname(__FILE__) . '/MyAllocator/Api/MaApi.php');

// Configuration
foreach (glob(dirname(__FILE__) . '/MyAllocator/Config/*.php') as $file) {
    require_once($file);
}

// Exceptions
foreach (glob(dirname(__FILE__) . '/MyAllocator/Exception/*.php') as $file) {
    require_once($file);
}

// Utilities
foreach (glob(dirname(__FILE__) . '/MyAllocator/Util/*.php') as $file) {
    require_once($file);
}

// Objects
foreach (glob(dirname(__FILE__) . '/MyAllocator/Object/*.php') as $file) {
    require_once($file);
}

// APIs
foreach (glob(dirname(__FILE__) . '/MyAllocator/Api/*.php') as $file) {
    require_once($file);
}
