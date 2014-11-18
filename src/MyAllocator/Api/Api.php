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

namespace MyAllocator\phpsdk\Api;
use MyAllocator\phpsdk\Object\Auth as Auth;

/**
 * @todo make abstract
 */
class Api
{
    /**
     * @var \MyAllocator\Object\Auth Authentication object for requester.
     */
    protected $auth = null;

    /**
     * @var array Array of required authentication keys (string) for API method.
     */
    protected $auth_keys = array();

    /**
     * @var mixed The response from the last request.
     */
    private $lastApiResponse = null;

    public function __construct($cfg = null)
    {
        if (isset($cfg) && isset($cfg['auth'])) {
            if (is_array($cfg['auth'])) {
                $auth = new Auth();
                $auth_refl = new \ReflectionClass($auth);
                $props = $auth_refl->getProperties(\ReflectionProperty::IS_PUBLIC);

                foreach ($props as $prop) {
                    $name = $prop->getName();
                    if (isset($cfg['auth'][$name])) {
                        $auth->$name = $cfg['auth'][$name];
                    }
                }

                $this->auth = $auth;
            } else if (is_object($cfg['auth']) && is_a($cfg['auth'], 'MyAllocator\phpsdk\Object\Auth')) {
                $this->auth = $cfg['auth'];
            }
        }
    }

    /**
     * Get the authentication object for the API.
     *
     * @return MyAllocator\phpsdk\Object\Auth API Authentication object.
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Set the authentication object for the API.
     *
     * @param MyAllocator\phpsdk\Object\Auth API Authentication object.
     */
    public function setAuth(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get the last API response as array($rbody, $rcode).
     *
     * @return array
     */
    public static function getLastApiResponse()
    {
        return $this->lastApiResponse;
    }
}
