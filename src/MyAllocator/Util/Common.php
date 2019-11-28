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

namespace MyAllocator\phpsdk\src\Util;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Exception\ApiException;

/**
 * Common methods used in the SDK.
 */
abstract class Common
{
    /**
     * Get an auth object from the local ENV or test data.
     *
     * @param array $keys The authentication keys to set.
     * @param boolean $debug Enable debug.
     *
     * @return array(Auth, string) Authentication object and bool if pulled from ENV.
     *
     * @throws \MyAllocator\phpsdk\src\Exception\ApiException If no keys supplied.
     */
    public static function getAuthEnv($keys = null, $debug = false)
    {
        if (!$keys) {
            $msg = 'Keys parameter is required. (Keys to set from env)';
            throw new ApiException($msg);
        }

        /*
         * Some tests require all parameters to be from environment.
         * Otherwise, they are skipped.
         */
        $env = true;

        /*
         * To use, export the required environment vars:
         *   export ma_vendorId=VALUE
         *   export ma_vendorPassword=VALUE
         *   export ma_userId=VALUE
         *   export ma_userPassword=VALUE
         *   export ma_userToken=VALUE
         *   export ma_propertyId=VALUE
         *   export ma_PMSUserId=VALUE
         *   export ma_PMSPropertyId=VALUE
         */
        foreach ($keys as $k) {
            if (!($$k = getenv('ma_'.$k))) {
                // Key does not exist in environment, use test data
                $$k = '111';
                $env = false;
            }
        }

        $auth = new Auth();
        $auth->vendorId = isset($vendorId) ? $vendorId : null;
        $auth->vendorPassword = isset($vendorPassword) ? $vendorPassword : null;
        $auth->userId = isset($userId) ? $userId: null;
        $auth->userPassword = isset($userPassword) ? $userPassword : null;
        $auth->userToken = isset($userToken) ? $userToken : null;
        $auth->PMSUserId = isset($PMSUserId) ? $PMSUserId : null;
        $auth->propertyId = isset($propertyId) ? $propertyId : null;
        $auth->PMSPropertyId = isset($PMSPropertyId) ? $PMSPropertyId : null;
        $auth->debug = $debug;

        return array(
            'auth' => $auth,
            'from_env' => $env
        );
    }

    /**
     * Returns the name of a class using get_class with the namespaces stripped.
     *
     * @param object|string $object Object or Class Name to retrieve name
     *
     * @return string Name of class with namespaces stripped
     */
    public static function getClassName($object = null)
    {
        if (!is_object($object) && !is_string($object)) {
            return false;
        }

        $class = explode('\\', (is_string($object) ? $object : get_class($object)));
        return $class[count($class) - 1];
    }
}
