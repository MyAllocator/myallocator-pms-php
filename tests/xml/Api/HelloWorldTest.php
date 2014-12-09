<?php

namespace MyAllocator\phpsdk\tests\xml;
 
use MyAllocator\phpsdk\src\Api\HelloWorld;
use MyAllocator\phpsdk\src\Object\Auth;
 
class HelloWorldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new HelloWorld();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\HelloWorld', get_class($obj));
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
        $obj = new HelloWorld();
        $obj->setConfig('dataFormat', 'xml');

        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <HelloWorld>
                    <Auth></Auth>
                    <hello>world</hello>
                </HelloWorld>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
