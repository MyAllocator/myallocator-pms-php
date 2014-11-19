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
    public function testConstructorAuthCfgObject(array $fxt)
    {
        $cfg['auth'] = $fxt['auth'];
        $obj = new HelloWorld($cfg);
        $obj_auth = $obj->getAuth();

        foreach ($fxt['auth'] as $k => $v) {
            $this->assertEquals($cfg['auth']->$k, $obj_auth->$k);
        }
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureAuthCfgObject
     */
    public function testSayHello(array $fxt)
    {
        $obj = new HelloWorld($fxt);
        $rsp = $obj->sayHello($fxt['params']);
        $this->assertEquals(array('hello'), array_keys($rsp));
        $this->assertEquals('world', $rsp['hello']);
    }
}
