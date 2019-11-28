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

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\UserCreate;
use MyAllocator\phpsdk\src\Util\Common;
 
class UserCreateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new UserCreate();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\UserCreate', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword'
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

        $obj = new UserCreate($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No user id should fail
        $caught = false;
        try {
            $obj->callApiWithParams(array(
                'CustomerEmail' => 'phpsdkcustomer@phpsdk.com',
                'Email' => 'phpsdkuser@phpsdk.com',
                'UserPassword' => 'phpsdkpassword',
            ));
        } catch (\exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // Successful call with optional parameters
        $rsp = $obj->callApiWithParams(array(
            'UserId' => 'phpsdkuser',
            'UserPassword' => 'asfjksjkfsd',
            'Email' => 'phpsdkuser@phpsdk.com',
            'CustomerEmail' => 'phpsdkuser@phpsdk.com',
            'CustomerFirstName' => 'Bob',
            'CustomerLastName' => 'Smith',
            'SendWelcome' => 0
        ));

        $this->assertTrue(isset($rsp['response']['body']['UserIdExists']));
        $this->assertEquals($rsp['response']['body']['UserIdExists'], 'false');
        $this->assertTrue(isset($rsp['response']['body']['Success']));
        $this->assertEquals($rsp['response']['body']['Success'], 'true');
    }
}
