<?php

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\VendorSet;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
 
class VendorSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new VendorSet();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\VendorSet', get_class($obj));
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

        $obj = new VendorSet($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No password should fail
        $caught = false;
        try {
            $rsp = $obj->callApiWithParams(array(
                'Callback/URL' => 'http://www.example.com/myApiReceiver'
            ));
            var_dump($rsp);
        } catch (\exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // Successful call
        $rsp = $obj->callApiWithParams(array(
            'Callback/URL' => 'http://www.fun.com/myApiReceiver',
            'Callback/Password' => 'password'
        ));

        $this->assertTrue(isset($rsp['response']['Success']));
        $this->assertEquals($rsp['response']['Success'], 'true');
    }
}
