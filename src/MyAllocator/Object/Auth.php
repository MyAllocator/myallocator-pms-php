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

namespace MyAllocator\phpsdk\Object;
use MyAllocator\phpsdk\Exception\ApiException;

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
     * @var string The MyAllocator property id.
     */
    public $propertyId = null;

    /**
     * @var string The system property id.
     */
    public $PMSPropertyId = null;

    /**
     * Get the authentication variable from API key/parameter.
     *
     * @return mixed The requested API variable.
     */
    public function getAuthKeyVar($key)
    {
        switch ($key) {
            case 'Auth/VendorId':
                return $this->vendorId;
                break;
            case 'Auth/VendorPassword':
                return $this->vendorPassword;
                break;
            case 'Auth/UserId':
                return $this->userId;
                break;
            case 'PMSUserId':
                return $this->PMSUserId;
                break;
            case 'Auth/UserPassword':
                return $this->userPassword;
                break;
            case 'Auth/UserToken':
                return $this->userToken;
                break;
            case 'Auth/PropertyId':
                return $this->propertyId;
                break;
            case 'PMSPropertyId':
                return $this->PMSPropertyId;
                break;
            default:
                break;
        }
        $msg = 'Invalid Auth key requested: ' . $key;
        throw new ApiException($msg);
    }
}
