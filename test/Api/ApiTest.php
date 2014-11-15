<?php
 
use MyAllocator\phpsdk\Api\Api;
use MyAllocator\phpsdk\Object\Auth as Auth;
 
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
            'propertyIdMyAllocator' => '1111',
            'propertyIdSystem' => '1212',
            'debug' => false
        );

        $auth = new Auth();
        $auth->vendorId = $cfg_set['auth']['vendorId'];
        $auth->vendorPassword = $cfg_set['auth']['vendorPassword'];
        $auth->userId = $cfg_set['auth']['userId'];
        $auth->userPassword = $cfg_set['auth']['userPassword'];
        $auth->propertyIdMyAllocator = $cfg_set['auth']['propertyIdMyAllocator'];
        $auth->propertyIdSystem = $cfg_set['auth']['propertyIdSystem'];
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
            'propertyIdMyAllocator' => '555',
            'propertyIdSystem' => '666',
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
            'propertyIdMyAllocator' => '1111',
            'propertyIdSystem' => '1212',
            'debug' => false
        );

        $auth = new Auth();
        $auth->vendorId = $cfg_set['auth']['vendorId'];
        $auth->vendorPassword = $cfg_set['auth']['vendorPassword'];
        $auth->userId = $cfg_set['auth']['userId'];
        $auth->userPassword = $cfg_set['auth']['userPassword'];
        $auth->propertyIdMyAllocator = $cfg_set['auth']['propertyIdMyAllocator'];
        $auth->propertyIdSystem = $cfg_set['auth']['propertyIdSystem'];
        $auth->debug = $cfg_set['auth']['debug'];
        $obj->setAuth($auth);

        $obj_auth = $obj->getAuth();
        foreach ($cfg_set['auth'] as $k => $v) {
            $this->assertEquals($cfg_set['auth'][$k], $obj_auth->$k);
        }
    }
}
