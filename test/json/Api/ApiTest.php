<?php
 
use MyAllocator\phpsdk\Api\Api;
use MyAllocator\phpsdk\Object\Auth;
 
class ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new Api();
        $this->assertEquals('MyAllocator\phpsdk\Api\Api', get_class($obj));
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
        $auth->debug = $cfg_set['auth']['debug'];

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
        $obj = new Api($cfg);
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
            'debug' => true
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
        $obj = new Api($cfg);
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
        $obj = new Api();

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

        $keys = array(
            'auth' => array(
                'req' => array(
                    'Auth/VendorId',
                    'Auth/VendorPassword',
                ),
                'opt' => array(
                    'Auth/UserId',
                    'Auth/UserPassword',
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
        $obj = new Api();

        // Null Auth
        $keys = $fxt['keys'];
        $params = $fxt['params']['required'];
        $caught = false;
        try {
            $obj->validateApiParameters($keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiAuthenticationException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        unset($obj);
        $cfg['auth'] = $fxt['auth'];
        $obj = new Api($cfg);

        // Null keys and parameters
        $keys = null;
        $params = null;
        $caught = false;
        try {
            $obj->validateApiParameters($keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Null keys
        $keys = null;
        $params = $fxt['params']['required'];
        $caught = false;
        try {
            $obj->validateApiParameters($keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Null params
        $keys = $fxt['keys'];
        $params = null;
        $caught = false;
        try {
            $obj->validateApiParameters($keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Empty array params
        $keys = $fxt['keys'];
        $params = array();
        $caught = false;
        try {
            $obj->validateApiParameters($keys, $params);
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Required params
        $keys = $fxt['keys'];
        $params = $fxt['params']['required'];
        $result = $obj->validateApiParameters($keys, $params);
        $num_auth_keys = count($fxt['keys']['auth']['req']) + count($fxt['keys']['auth']['opt']);
        $this->assertEquals(count($result), count($params) + $num_auth_keys);

        // Required and optional params
        $keys = $fxt['keys'];
        $params = $fxt['params']['required_optional'];
        $result = $obj->validateApiParameters($keys, $params);
        $num_auth_keys = count($fxt['keys']['auth']['req']) + count($fxt['keys']['auth']['opt']);
        $this->assertEquals(count($result), count($params) + $num_auth_keys);

        // Required, optional, and extra params
        $keys = $fxt['keys'];
        $params = $fxt['params']['required_optional_extra'];
        $result = $obj->validateApiParameters($keys, $params);
        $num_auth_keys = count($fxt['keys']['auth']['req']) + count($fxt['keys']['auth']['opt']);
        $this->assertEquals(count($result), count($params) - 2 + $num_auth_keys);
    }
}
