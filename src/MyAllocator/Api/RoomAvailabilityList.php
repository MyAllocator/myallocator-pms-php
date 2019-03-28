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

namespace MyAllocator\phpsdk\src\Api;

/**
 * Query the Availability/Rates/Inventory for all rooms under a property account.
 */
class RoomAvailabilityList extends MaApi
{
    /**
     * @var string The API endpoint to call.
     */
    protected $id = 'RoomAvailabilityList';

    /**
     * @var array Array of required and optional authentication and argument
     *      keys (string) for API method.
     */
    protected $keys = array(
        'auth' => array(
            'req' => array(
                'Auth/PropertyId',
                'Auth/UserToken',
                'Auth/VendorId',
                'Auth/VendorPassword',
            ),
            'opt' => array()
        ),
        'args' => array(
            'req' => array(
                'StartDate',
                'EndDate'
            ),
            'opt' => array()
        )
    );
}
