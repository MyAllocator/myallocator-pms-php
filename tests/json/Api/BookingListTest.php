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

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\BookingList;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
use MyAllocator\phpsdk\src\Exception\ApiException;
 
class BookingListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new BookingList();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\BookingList', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword',
            'userId',
            'userPassword',
            'propertyId'
        ));
        $data = array();
        $data[] = array($auth);

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureAuthCfgObject
     */
    public function testCallApi(array $fxt)
    {
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new BookingList($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No optional parameters should throw exception
        $caught = false;
        try {
            $rsp = $obj->callApi();
        } catch (\exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

/*
        // Arrival parameters
        $rsp = $obj->callApiWithParams(array(
            'ArrivalStartDate' => '2014-11-01',
            'ArrivalEndDate' => '2014-12-30'
        ));
        $this->assertTrue(isset($rsp['response']['body']['Bookings']));

        // Modification parameters
        $rsp = $obj->callApiWithParams(array(
            'ModificationStartDate' => '2014-11-01',
            'ModificationEndDate' => '2014-12-30'
        ));
        $this->assertTrue(isset($rsp['response']['body']['Bookings']));
*/

        // Creation parameters
        $rsp = $obj->callApiWithParams(array(
            'ModificationStartDateTime' => '2015-01-01 00:00:00',
            'ModificationEndDateTime' => '2015-07-30 00:00:00'
//            'Options' => array(
//                'IncludeArchived' => 'true'
//            )
        ));

/*
        // Creation parameters
        $rsp = $obj->callApiWithParams(array(
            'CreationStartDate' => '2015-01-01',
            'CreationEndDate' => '2015-08-01',
            'Options' => array(
                'IncludeArchived' => 'true'
            )
        ));
*/
        $this->assertTrue(isset($rsp['response']['body']['Bookings']));
    }
}
