<?php
 
use MyAllocator\phpsdk\Api\HelloVendorUser;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class HelloVendorUserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new HelloVendorUser();
        $this->assertEquals('MyAllocator\phpsdk\Api\HelloVendorUser', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId', 
            'vendorPassword',
            'userId', 
            'userPassword'
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

        // Successful call
        $obj = new HelloVendorUser($fxt);
        $obj->setConfig('dataFormat', 'array');
        $rsp = $obj->callApiWithParams(array(
            'hello' => 'world'
        ));
        $this->assertTrue(isset($rsp['response']['hello']));
        $this->assertEquals('world', $rsp['response']['hello']);
    }
}
