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
 
use MyAllocator\phpsdk\src\Api\RoomImageCreate;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
 
class RoomImageCreateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new RoomImageCreate();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\RoomImageCreate', get_class($obj));
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

        $obj = new RoomImageCreate($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Valid order id and valid password should succeed
        $rsp = $obj->callApiWithParams(array(
            'RoomImages' => array(
                array(
                    'RoomId' => '22905',
                    'Filename' => 'test-room.jpg',
                    'Data' => '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'
                )
            )
        ));
        $this->assertEquals($rsp['response']['body']['Success'], 1);
    }
}
