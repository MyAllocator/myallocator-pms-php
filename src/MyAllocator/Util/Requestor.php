<?php
/**
 * Copyright (C) 2014 MyAllocator
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

namespace MyAllocator\phpsdk\Util;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiException;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
use MyAllocator\phpsdk\Exception\ApiConnectionException;
use MyAllocator\phpsdk\Exception\InvalidRequestException;

/**
 * The Requestor class is responsible for preparing and sending an API reqest,
 * as well as parsing and handling the response.
 */
class Requestor
{
    /**
     * @var string The MyAllocator API base url.
     */
    private $apiBase = 'api.myallocator.com';
    //private $apiBase = '54.68.116.212';

    /**
     * @var string The API version. 
     */
    public $version = '201408';

    /**
     * @var mixed The response from the last request.
     */
    private $lastApiResponse = null;

    public function __construct() {}

    /**
     * Get the last API response as array($rbody, $rcode).
     *
     * @return array
     */
    public static function getLastApiResponse()
    {
        return $this->lastApiResponse;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $params
     *
     * @return array An array whose first element is the response and second
     *    element is the API key used to make the request.
     */
    public function request($method, $url, $params=null)
    {
        if (!$params) {
            $params = array();
        }

        list($rbody, $rcode) = $this->prepareRequest($method, $url, $params);
        $resp = $this->interpretResponse($rbody, $rcode);
        return $resp;
    }

    private function prepareRequest($method, $url, $params)
    {
        $params = self::encodeObjects($params);
        $absUrl = $this->apiUrl($url);

        list($rbody, $rcode) = $this->curlRequest(
            $method,
            $absUrl,
            $params
        );

        return array($rbody, $rcode);
    }

    private function curlRequest($method, $absUrl, $params)
    {
        $opts = array();
        $curl = curl_init();

        if ($method == 'get') {
            $opts[CURLOPT_HTTPGET] = 1;
            if (count($params) > 0) {
                $encoded = self::encode($params);
                $absUrl = "$absUrl?$encoded";
            }
        } else if ($method == 'post') {
            try {
                $encoded = json_encode($params);
            } catch (Exception $e) {
                $msg = 'JSON Encode Error - Invalid parameters: '.serialize($params);
                throw new ApiException($msg, $rcode, $rbody);
            }
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = 'json=' . $encoded;
        } else {
            throw new ApiException("Unrecognized method $method");
        }

        $absUrl = self::utf8($absUrl);
        $opts[CURLOPT_URL] = $absUrl;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT] = 60;
        $opts[CURLOPT_HEADER] = false;
        $opts[CURLOPT_USERAGENT] = 'PHP SDK/1.0';

        //var_dump($absUrl);
        //var_dump($opts[CURLOPT_POSTFIELDS]);

        curl_setopt_array($curl, $opts);
        $rbody = curl_exec($curl);

        if ($rbody === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($errno, $message);
        } else if (!preg_match("/^[\s\n\r]*\{.*\}[\s\n\r]*$/s", $rbody))  {
            $rbody = array(
                'Errors' => array(array(
                    'ErrorId' => 1,
                    'ErrorMsg' => 'Invalid JSON Response (server error)',
                    'ErrorDetail' => $rbody
                ))
            ); 
        }

        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $response = array($rbody, $rcode);
        $this->lastApiResponse = $response;
        return $response;
    }

    private function interpretResponse($rbody, $rcode)
    {
        try {
            $resp = json_decode($rbody, TRUE); 
        } catch (Exception $e) {
            $msg = "Invalid response body from API: $rbody "
                . "(HTTP response code was $rcode)";
            throw new ApiException($msg, $rcode, $rbody);
        }

        if ($rcode < 200 || $rcode >= 300) {
            $this->handleHttpError($rbody, $rcode, $resp);
        }

        return $resp;
    }

    private function handleCurlError($errno, $message)
    {
        $apiBase = $this->apiBase;
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to MyAllocator ($apiBase).  Please check your "
                    . "internet connection and try again.";
                break;
            default:
                $msg = "Unexpected error communicating with MyAllocator.  "
                    . "If this problem persists,";
        }
        $msg .= " let us know at support@myallocator.com.";

        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new ApiConnectionException($msg);
    }

    private function handleHttpError($rbody, $rcode, $resp)
    {
        if (!is_array($resp) || !isset($resp['Errors'])) {
            $msg = "Invalid response object from API: $rbody "
                ."(HTTP response code was $rcode)";
            throw new ApiException($msg, $rcode, $rbody, $resp);
        }

        switch ($rcode) {
            case 404:
                throw new InvalidRequestException($msg, $rcode, $rbody, $resp);
            case 401:
                throw new ApiAuthenticationException($msg, $rcode, $rbody, $resp);
            default:
                throw new ApiException($msg, $rcode, $rbody, $resp);
        }
    }

    private function apiUrl($url='')
    {
        if (!$url) {
            return false;
        }

        $absUrl = sprintf("https://%s/pms/v%d/json/%s", 
                          $this->apiBase,
                          $this->version,
                          $url);

        return (string) $absUrl;
    }

    public static function utf8($value)
    {
        if (is_string($value)
                && mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8") {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }

    public static function encode($arr, $prefix=null)
    {
        if (!is_array($arr))
            return $arr;

        $r = array();
        foreach ($arr as $k => $v) {
            if (is_null($v))
                continue;

            if ($prefix && $k && !is_int($k))
                $k = $prefix."[".$k."]";
            else if ($prefix)
                $k = $prefix."[]";

            if (is_array($v)) {
                $r[] = self::encode($v, $k, true);
            } else {
                $r[] = urlencode($k)."=".urlencode($v);
            }
        }

        return implode("&", $r);
    }

    private static function encodeObjects($d)
    {
        if ($d instanceof Api) {
            return self::utf8(Common::get_class_name(get_class($d)));
        } else if ($d === true) {
            return 'true';
        } else if ($d === false) {
            return 'false';
        } else if (is_array($d)) {
            $res = array();
            foreach ($d as $k => $v) {
                $res[$k] = self::encodeObjects($v);
            }
            return $res;
        } else {
            return self::utf8($d);
        }
    }
}
