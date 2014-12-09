<?php

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\HelloVendor;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
 
class HelloVendorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new HelloVendor();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\HelloVendor', get_class($obj));
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

        // Null auth
        $obj = new HelloVendor();
        $obj->setConfig('dataFormat', 'array');
        try {
            $rsp = $obj->callApiWithParams(array(
                'hello' => 'world'
            ));
        } catch (Exception $e) {
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiAuthenticationException', $e);
        }

        // Successful call
        $obj = new HelloVendor($fxt);
        $obj->setConfig('dataFormat', 'array');
        $rsp = $obj->callApiWithParams(array(
            'hello' => 'world'
        ));
        $this->assertTrue(isset($rsp['response']['hello']));
        $this->assertEquals('world', $rsp['response']['hello']);
    }
}
