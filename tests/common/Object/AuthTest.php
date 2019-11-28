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
 
use MyAllocator\phpsdk\src\Object\Auth;
 
class AuthTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new Auth();
        $this->assertEquals('MyAllocator\phpsdk\src\Object\Auth', get_class($obj));
    }

    public function fixtureObjectProperties()
    {
        $data = array();
        $data[] = array(array(
            'vendorId' => '777',
            'vendorPassword' => '888',
            'userId' => '999',
            'userPassword' => '1010',
            'userToken' => '1313',
            'propertyId' => '1111',
            'PMSPropertyId' => '1212',
        ));

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @group object
     * @dataProvider fixtureObjectProperties
     */
    public function testObjectProperties(array $fxt)
    {
        $auth = new Auth();
        $auth->vendorId = $fxt['vendorId'];
        $auth->vendorPassword = $fxt['vendorPassword'];
        $auth->userId = $fxt['userId'];
        $auth->userPassword = $fxt['userPassword'];
        $auth->userToken = $fxt['userToken'];
        $auth->propertyId = $fxt['propertyId'];
        $auth->PMSPropertyId = $fxt['PMSPropertyId'];

        $this->assertEquals($auth->vendorId, $fxt['vendorId']);
        $this->assertEquals($auth->vendorPassword, $fxt['vendorPassword']);
        $this->assertEquals($auth->userId, $fxt['userId']);
        $this->assertEquals($auth->userPassword, $fxt['userPassword']);
        $this->assertEquals($auth->userToken, $fxt['userToken']);
        $this->assertEquals($auth->propertyId, $fxt['propertyId']);
        $this->assertEquals($auth->PMSPropertyId, $fxt['PMSPropertyId']);
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureObjectProperties
     */
    public function testGetAuthKeyVar(array $fxt)
    {
        $auth = new Auth();
        $auth->vendorId = $fxt['vendorId'];
        $auth->vendorPassword = $fxt['vendorPassword'];
        $auth->userId = $fxt['userId'];
        $auth->userPassword = $fxt['userPassword'];
        $auth->userToken = $fxt['userToken'];
        $auth->propertyId = $fxt['propertyId'];
        $auth->PMSPropertyId = $fxt['PMSPropertyId'];

        // Test valid authentication keys
        $this->assertEquals($auth->vendorId, $auth->getAuthKeyVar('Auth/VendorId'));
        $this->assertEquals($auth->vendorPassword, $auth->getAuthKeyVar('Auth/VendorPassword'));
        $this->assertEquals($auth->userId, $auth->getAuthKeyVar('Auth/UserId'));
        $this->assertEquals($auth->userPassword, $auth->getAuthKeyVar('Auth/UserPassword'));
        $this->assertEquals($auth->userToken, $auth->getAuthKeyVar('Auth/UserToken'));
        $this->assertEquals($auth->propertyId, $auth->getAuthKeyVar('Auth/PropertyId'));
        $this->assertEquals($auth->PMSPropertyId, $auth->getAuthKeyVar('PMSPropertyId'));

        // Test invalid key
        try {
            $auth->getAuthKeyVar('invalid_key');
        } catch (Exception $e) {
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        // Test api with no auth set
        unset($auth);
        $auth = new Auth();
        $this->assertEquals(null, $auth->getAuthKeyVar('Auth/VendorId'));
    }
}
