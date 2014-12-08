<?php
 
use MyAllocator\phpsdk\Api\HelloWorld;
use MyAllocator\phpsdk\Object\Auth as Auth;
 
class HelloWorldTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new HelloWorld();
        $this->assertEquals('MyAllocator\phpsdk\Api\HelloWorld', get_class($obj));
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
            'auth' => $auth,
            'params' => array(
                'Auth' => 'wee',
                'hello' => 'world'
            )
        ));

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureAuthCfgObject
     */
    public function testCallApi(array $fxt)
    {
        $obj = new HelloWorld($fxt);
        $rsp = $obj->callApiWithParams($fxt['params']);
        $this->assertTrue(isset($rsp['response']['hello']));
        $this->assertEquals('world', $rsp['response']['hello']);
    }
}
