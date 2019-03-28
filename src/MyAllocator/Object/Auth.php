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

namespace MyAllocator\phpsdk\src\Object;
use MyAllocator\phpsdk\src\Exception\ApiException;

/**
 * The authentication class used to store authentication parameters
 * to be used in one or more API requests.
 */
class Auth
{
    /**
     * @var string The vendor id (Required without user credentials).
     */
    public $vendorId = null;

    /**
     * @var string The vendor password (Required without user credentials).
     */
    public $vendorPassword = null;

    /**
     * @var string The user id (Required without vendor credentials).
     */
    public $userId = null;

    /**
     * @var string The system user id.
     */
    public $PMSUserId = null;

    /**
     * @var string The user password (Required without vendor credentials).
     */
    public $userPassword = null;

    /**
     * @var string The user token (can store/use instead of username/password).
     */
    public $userToken = null;

    /**
     * @var string The myallocator property id.
     */
    public $propertyId = null;

    /**
     * @var string The system property id.
     */
    public $PMSPropertyId = null;

    /**
     * @var array API Authentication to property mappings.
     */
    private $authKeyMap = array(
        'Auth/VendorId' => 'vendorId',
        'Auth/VendorPassword' => 'vendorPassword',
        'Auth/UserId' => 'userId',
        'PMSUserId' => 'PMSUserId',
        'Auth/UserPassword' => 'userPassword',
        'Auth/UserToken' => 'userToken',
        'Auth/PropertyId' => 'propertyId',
        'PMSPropertyId' => 'PMSPropertyId'
    );

    /**
     * @var boolean Enable/disable debug mode
     */
    public $debug = null;

    /**
     * Map an API authentication key to the Auth object's
     * variable and return it.
     *
     * @param string $key The API authentication key.
     *
     * @return mixed The requested API variable.
     *
     * @throws \MyAllocator\phpsdk\src\Exception\ApiException
     */
    public function getAuthKeyVar($key)
    {
        if (!isset($this->authKeyMap[$key])) {
            $msg = 'Invalid Auth key requested: ' . $key;
            throw new ApiException($msg);
        }

        $property = $this->authKeyMap[$key];
        return $this->$property;
    }

    /**
     * Get an authentication key by variable name.
     *
     * @param string $property The property/variable name.
     *
     * @return string The authentication key.
     *
     * @throws \MyAllocator\phpsdk\src\Exception\ApiException
     */
    public function getAuthKeyByVar($property)
    {
        if (!($key = array_search($property, $this->authKeyMap))) {
            $msg = 'Invalid Auth property requested: ' . $property;
            throw new ApiException($msg);
        }

        return $key;
    }
}
