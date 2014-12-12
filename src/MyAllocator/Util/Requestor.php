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

namespace MyAllocator\phpsdk\src\Util;
use MyAllocator\phpsdk\src\MaBaseClass;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiException;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
use MyAllocator\phpsdk\src\Exception\ApiConnectionException;
use MyAllocator\phpsdk\src\Exception\InvalidRequestException;

/**
 * The Requestor class is responsible for preparing and sending an API reqest,
 * as well as parsing and handling the response.
 */
class Requestor extends MaBaseClass
{
    /**
     * @var string The MyAllocator API base url.
     */
    private $apiBase = 'api.myallocator.com';

    /**
     * @var string The API version. 
     */
    public $version = '201408';

    /**
     * The constructor passes potential configuration parameters to MaBaseClass.
     *
     * @param array $cfg API configuration parameters.
     */
    public function __construct($cfg = null)
    {
        parent::__construct(array(
            'cfg' => $cfg
        ));
        $this->debug_echo("\n\nConfiguration:\n");
        $this->debug_print_r($this->config);
    }

    /**
     * Send an API request, interpret and return the response.
     *
     * @param string $method HTTP method.
     * @param string $url API endpoint.
     * @param array|null $params API parameters.
     *
     * @return mixed API response, code, headers (XML only).
     *
     * @throws MyAllocator\phpsdk\src\Exception\ApiException
     */
    public function request($method, $url, $params = null)
    {
        if (!$params) {
            $params = array();
        } else {
            $params = self::encodeObjects($params);
        }

        /*
         * Send request based on dataFormat. Format 'array'
         * is json_encoded and sent as json.
         */
        $this->debug_echo("\nRequest (".$this->config['dataFormat']."):\n");
        $headers = null;
        switch ($this->config['dataFormat']) {
            case 'array':
                $this->debug_print_r($params); 
                try {
                    $params = json_encode($params);
                } catch (Exception $e) {
                    $msg = 'JSON Encode Error - Invalid parameters: '.serialize($params);
                    throw new ApiException($msg, $rcode, $rbody);
                }
                $this->debug_echo("\nRequest (json):\n");
                // Intentionally dropping into json case
            case 'json':
                $this->debug_echo($params); 
                $absUrl = $this->apiUrl($url, 'json');
                list($rbody, $rcode) = $this->curlRequestJSON(
                    $method,
                    $absUrl,
                    $params
                );
                $resp = $this->interpretResponseJSON($rbody, $rcode);
                break;
            case 'xml':
                $this->debug_echo($params); 
                $absUrl = $this->apiUrl($url, 'xml');
                list($rbody, $rcode, $headers) = $this->curlRequestXML(
                    $method,
                    $absUrl,
                    $params
                );
                $resp = $this->interpretResponseXML($rbody, $rcode);
                break;
            default:
                $msg = 'Invalid data format: '.$this->config['dataFormat'];
                throw new ApiException($msg);
        }

        return array(
            'code' => $rcode,
            'headers' => $headers,
            'response' => $resp
        );
    }

    /**
     * Send a JSON CURL request.
     *
     * @param string $method HTTP method.
     * @param string $absUrl The absolute endpoint URL.
     * @param array|null $params API parameters.
     *
     * @return mixed API response, code, headers (XML only).
     *
     * @throws MyAllocator\phpsdk\src\Exception\ApiException
     */
    private function curlRequestJSON($method, $absUrl, $params)
    {
        $opts = array();
        $curl = curl_init();

        if ($method == 'post') {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = array('json' => $params);
        } else {
            throw new ApiException('Unsupported method '.$method);
        }

        $absUrl = self::utf8($absUrl);
        $opts[CURLOPT_URL] = $absUrl;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT] = 60;
        $opts[CURLOPT_HEADER] = false;
        $opts[CURLOPT_USERAGENT] = 'PHP SDK/1.0';

        curl_setopt_array($curl, $opts);
        $rbody = curl_exec($curl);

        if ($rbody === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($errno, $message);
        }

        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return array($rbody, $rcode);
    }

    /**
     * Process a JSON CURL response.
     *
     * @param string $rbody The JSON response body.
     * @param integer $rcode The HTTP code.
     *
     * @return mixed API response. The format depends on dataFormat.
     *
     * @throws MyAllocator\phpsdk\src\Exception\ApiException
     */
    private function interpretResponseJSON($rbody, $rcode)
    {
        $this->debug_echo("\n\nResponse (json):\n");
        $this->debug_print_r($rbody);

        // Convert response from json to array format if required
        if ($this->config['dataFormat'] == 'array') {
            try {
                $resp = json_decode($rbody, TRUE); 
            } catch (Exception $e) {
                $msg = 'Invalid response body from API: ' . $rbody
                     . '(HTTP response code was ' . $rcode . ')';
                throw new ApiException($msg, $rcode, $rbody);
            }
            $this->debug_echo("\n\nResponse (array):\n"); 
            $this->debug_print_r($resp); 
        } else {
            $resp = $rbody;
        }

        if ($rcode < 200 || $rcode >= 300) {
            $this->handleHttpError($rbody, $rcode, $resp);
        }

        return $resp;
    }

    /**
     * Send a XML CURL request.
     *
     * @param string $method HTTP method.
     * @param string $absUrl The absolute endpoint URL.
     * @param array|null $params API parameters.
     *
     * @return mixed API response, code, headers (XML only).
     *
     * @throws MyAllocator\phpsdk\src\Exception\ApiException
     */
    private function curlRequestXML($method, $absUrl, $xml)
    {
        $opts = array();
        $curl = curl_init();

        if ($method == 'post') {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = 'xmlRequestString='.urlencode($xml);
        } else {
            throw new ApiException('Unsupported method '.$method);
        }

        $absUrl = self::utf8($absUrl);
        $opts[CURLOPT_URL] = $absUrl;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT] = 60;
        $opts[CURLOPT_HEADER] = true;
        $opts[CURLOPT_USERAGENT] = 'PHP SDK/1.0';

        curl_setopt_array($curl, $opts);
        $result = curl_exec($curl);

        if ($result === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($errno, $message);
        }

        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headers = substr($result, 0, $header_size);
        $rbody = substr($result, $header_size);
        curl_close($curl);

        $response = array($rbody, $rcode, $headers);
        return $response;
    }

    /**
     * Process a XML CURL response.
     *
     * @param string $rbody The JSON response body.
     * @param integer $rcode The HTTP code.
     *
     * @return mixed API response. The format depends on dataFormat.
     */
    private function interpretResponseXML($rbody, $rcode)
    {
        $this->debug_echo("\n\nResponse (xml):\n$rbody");

        if ($rcode < 200 || $rcode >= 300) {
            $this->handleHttpError($rbody, $rcode);
        }

        return $rbody;
    }

    /**
     * Handle a CURL error resulting from a request.
     *
     * @param integer $errno The CURL error code.
     * @param string $message The CURL error nessage.
     *
     * @throws MyAllocator\phpsdk\src\Exception\ApiConnectionException
     */
    private function handleCurlError($errno, $message)
    {
        $apiBase = $this->apiBase;
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = 'Could not connect to MyAllocator (' . $apiBase . '). '
                     . 'Please check your internet connection and try again.';
                break;
            default:
                $msg = 'Unexpected error communicating with MyAllocator. '
                     . 'If this problem persists,  let us know at '
                     . 'support@myallocator.com.';
        }

        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new ApiConnectionException($msg);
    }

    /**
     * Handle a HTTP error resulting from a request.
     *
     * @param mixed $rbody The HTTP response body.
     * @param integer $rcode The HTTP error code.
     * @param string $resp The JSON encoded response body.
     *
     * @throws MyAllocator\phpsdk\src\Exception\InvalidRequestException
     * @throws MyAllocator\phpsdk\src\Exception\APIAuthenticationException
     * @throws MyAllocator\phpsdk\src\Exception\ApiException
     */
    private function handleHttpError($rbody, $rcode, $resp = null)
    {
        $msg = 'HTTP API Error (HTTP response code was ' . $rcode . ')';
        switch ($rcode) {
            case 404:
                throw new InvalidRequestException($msg, $rcode, $rbody, $resp);
            case 401:
                throw new ApiAuthenticationException($msg, $rcode, $rbody, $resp);
            default:
                throw new ApiException($msg, $rcode, $rbody, $resp);
        }
    }

    /**
     * Get the absolute URL for an API request.
     *
     * @param string $url The API method endpoint.
     * @param string $format The request data format.
     *
     * @return string The absolute API URL.
     */
    private function apiUrl($url = '', $format = 'json')
    {
        if (!$url) {
            return false;
        }

        $absUrl = sprintf('https://%s/pms/v%d/%s/%s', 
                          $this->apiBase,
                          $this->version,
                          $format,
                          $url);

        return (string) $absUrl;
    }

    /**
     * Convert a string to UTF-8 encoding.
     *
     * @param string $value The string to encode.
     *
     * @return string The UTF-8 encoded string.
     */
    public static function utf8($value)
    {
        if (is_string($value) &&
            mb_detect_encoding($value, 'UTF-8', TRUE) != 'UTF-8'
        ) {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }

    /**
     * URL encode URL array parameters.
     *
     * @param array $arr The array to encode.
     * @param string $prefix A key prefix.
     *
     * @return string The URL encoded string.
     */
    public static function encode($arr, $prefix=null)
    {
        if (!is_array($arr)) {
            return $arr;
        }

        $r = array();
        foreach ($arr as $k => $v) {
            if (is_null($v)) {
                continue;
            }

            if ($prefix && $k && !is_int($k)) {
                $k = $prefix . '[' . $k . ']';
            } else if ($prefix) {
                $k = $prefix . '[]';
            }

            if (is_array($v)) {
                $r[] = self::encode($v, $k, true);
            } else {
                $r[] = urlencode($k) . '=' . urlencode($v);
            }
        }

        return implode('&', $r);
    }

    /**
     * Encode an object.
     *
     * @param mixed $d The object to encode.
     *
     * @return mixed The encoded object.
     */
    private static function encodeObjects($d)
    {
        if ($d instanceof Api) {
            return self::utf8(Common::getClassName(get_class($d)));
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
