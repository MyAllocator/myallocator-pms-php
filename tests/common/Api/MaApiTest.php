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
 
use MyAllocator\phpsdk\src\Api\MaApi;
use MyAllocator\phpsdk\src\Object\Auth;
 
class MaApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new MaApi();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\MaApi', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $cfg_set['auth'] = array(
            'vendorId' => '777',
            'vendorPassword' => '888',
            'userId' => '999',
            'userPassword' => '1010',
            'propertyId' => '1111',
            'PMSPropertyId' => '1212',
            'debug' => false
        );

        $auth = new Auth();
        $auth->vendorId = $cfg_set['auth']['vendorId'];
        $auth->vendorPassword = $cfg_set['auth']['vendorPassword'];
        $auth->userId = $cfg_set['auth']['userId'];
        $auth->userPassword = $cfg_set['auth']['userPassword'];
        $auth->propertyId = $cfg_set['auth']['propertyId'];
        $auth->PMSPropertyId = $cfg_set['auth']['PMSPropertyId'];

        $data = array();
        $data[] = array(array(
            'auth' => $auth
        ));

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureAuthCfgObject
     */
    public function testConstructorAuthCfgObject(array $fxt)
    {
        $cfg['auth'] = $fxt['auth'];
        $obj = new MaApi($cfg);
        $obj_auth = $obj->getAuth();

        foreach ($fxt['auth'] as $k => $v) {
            $this->assertEquals($cfg['auth']->$k, $obj_auth->$k);
        }
    }

    public function fixtureAuthCfgArray()
    {
        $data = array();

        $data[] = array(array(
            'vendorId' => '111',
            'vendorPassword' => '222',
            'userId' => '333',
            'userPassword' => '444',
            'propertyId' => '555',
            'PMSPropertyId' => '666',
        ));

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureAuthCfgArray
     */
    public function testConstructorAuthCfgArray(array $fxt)
    {
        $cfg['auth'] = $fxt;
        $obj = new MaApi($cfg);
        $obj_auth = $obj->getAuth();

        foreach ($cfg['auth'] as $k => $v) {
            $this->assertEquals($cfg['auth'][$k], $obj_auth->$k);
        }
    }

    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testSetAuth()
    {
        $obj = new MaApi();

        $cfg_set['auth'] = array(
            'vendorId' => '777',
            'vendorPassword' => '888',
            'userId' => '999',
            'userPassword' => '1010',
            'propertyId' => '1111',
            'PMSPropertyId' => '1212',
            'debug' => false
        );

        $auth = new Auth();
        $auth->vendorId = $cfg_set['auth']['vendorId'];
        $auth->vendorPassword = $cfg_set['auth']['vendorPassword'];
        $auth->userId = $cfg_set['auth']['userId'];
        $auth->userPassword = $cfg_set['auth']['userPassword'];
        $auth->propertyId = $cfg_set['auth']['propertyId'];
        $auth->PMSPropertyId = $cfg_set['auth']['PMSPropertyId'];
        $auth->debug = $cfg_set['auth']['debug'];
        $obj->setAuth($auth);

        $obj_auth = $obj->getAuth();
        foreach ($cfg_set['auth'] as $k => $v) {
            $this->assertEquals($cfg_set['auth'][$k], $obj_auth->$k);
        }
    }

    public function fixtureValidateApiParameters()
    {
        $cfg_set['auth'] = array(
            'vendorId' => '777',
            'vendorPassword' => '888',
            'userId' => '999',
            'userPassword' => '1010',
            'userToken' => '1313',
            'propertyId' => '1111',
            'PMSPropertyId' => '1212',
            'debug' => false
        );

        $auth = new Auth();
        $auth->vendorId = $cfg_set['auth']['vendorId'];
        $auth->vendorPassword = $cfg_set['auth']['vendorPassword'];
        $auth->userId = $cfg_set['auth']['userId'];
        $auth->userPassword = $cfg_set['auth']['userPassword'];
        $auth->userToken = $cfg_set['auth']['userToken'];
        $auth->propertyId = $cfg_set['auth']['propertyId'];
        $auth->PMSPropertyId = $cfg_set['auth']['PMSPropertyId'];
        $auth->debug = $cfg_set['auth']['debug'];

        $keys = array(
            'auth' => array(
                'req' => array(
                    'Auth/VendorId',
                    'Auth/VendorPassword',
                ),
                'opt' => array(
                    'Auth/UserId',
                    'Auth/UserPassword',
                    'Auth/UserToken',
                )
            ),
            'args' => array(
                'req' => array(
                    'hello'
                ),
                'opt' => array(
                    'goodbye'
                )
            )
        );

        $params = array(
            'required' => array(
                'hello' => 'world'
            ),
            'required_optional' => array(
                'hello' => 'world',
                'goodbye' => 'friend'
            ),
            'required_optional_extra' => array(
                'extra1' => 1,
                'hello' => 'world',
                'goodbye' => 'friend',
                'extra2' => 1
            )
        );

        $data = array();
        $data[] = array(array(
            'auth' => $auth,
            'keys' => $keys,
            'params' => $params
        ));

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureValidateApiParameters
     */
    public function testValidateApiParameters(array $fxt)
    {
        echo PHP_VERSION;
        // Reflection required to test private method
        if (!version_compare(PHP_VERSION, '5.3.2', '>=')) {
            $this->markTestSkipped('PHP version >= 5.3.2 required for Reflection in test.');        
        }

        $obj = new MaApi();
        $obj->setConfig('dataFormat', 'array');

        // Prepare reflection method to test private method
        $reflector = new ReflectionClass('MyAllocator\phpsdk\src\Api\MaApi');
        $ref_method_validate = $reflector->getMethod('validateApiParameters');
        $ref_method_validate->setAccessible(true);

        // Null Auth
        $keys = $fxt['keys'];
        $params = $fxt['params']['required'];
        $caught = false;
        try {
            $ref_method_validate->invoke($obj, $keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiAuthenticationException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        unset($obj);
        $cfg['auth'] = $fxt['auth'];
        $obj = new MaApi($cfg);
        $obj->setConfig('dataFormat', 'array');

        // Null keys and parameters
        $keys = null;
        $params = null;
        $caught = false;
        try {
            $ref_method_validate->invoke($obj, $keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Null keys
        $keys = null;
        $params = $fxt['params']['required'];
        $caught = false;
        try {
            $ref_method_validate->invoke($obj, $keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Null params
        $keys = $fxt['keys'];
        $params = null;
        $caught = false;
        try {
            $ref_method_validate->invoke($obj, $keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Empty array params
        $keys = $fxt['keys'];
        $params = array();
        $caught = false;
        try {
            $ref_method_validate->invoke($obj, $keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Required params
        $keys = $fxt['keys'];
        $params = $fxt['params']['required'];
        $result = $ref_method_validate->invoke($obj, $keys, $params);
        $num_auth_keys = count($fxt['keys']['auth']['req']) + count($fxt['keys']['auth']['opt']);
        $this->assertEquals(count($result), count($params) + $num_auth_keys);

        // Required and optional params
        $keys = $fxt['keys'];
        $params = $fxt['params']['required_optional'];
        $result = $ref_method_validate->invoke($obj, $keys, $params);
        $num_auth_keys = count($fxt['keys']['auth']['req']) + count($fxt['keys']['auth']['opt']);
        $this->assertEquals(count($result), count($params) + $num_auth_keys);

        // Required, optional, and extra params
        $keys = $fxt['keys'];
        $params = $fxt['params']['required_optional_extra'];
        $result = $ref_method_validate->invoke($obj, $keys, $params);
        $num_auth_keys = count($fxt['keys']['auth']['req']) + count($fxt['keys']['auth']['opt']);
        $this->assertEquals(count($result), count($params) + $num_auth_keys);
    }
}
