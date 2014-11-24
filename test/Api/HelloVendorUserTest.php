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
        $auth = Common::get_auth_env(array(
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

        // Auth null
        $obj = new HelloVendorUser();
        try {
            $rsp = $obj->callApiWithParams(array(
                'hello' => 'world'
            ));
        } catch (Exception $e) {
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiAuthenticationException', $e);
        }

        // Invalid auth
        $fxt['auth']->vendorId = '111';
        $fxt['auth']->vendorPassword = '111';
        $fxt['auth']->userId = '111';
        $fxt['auth']->userPassword = '111';
        $obj = new HelloVendorUser($fxt);
        $rsp = $obj->callApiWithParams(array(
            'hello' => 'world'
        ));
        $this->assertTrue(isset($rsp['Errors']));
        $this->assertTrue(isset($rsp['Errors'][0]['ErrorMsg']));
        $this->assertEquals('Invalid vendor or vendor password', $rsp['Errors'][0]['ErrorMsg']);

        // Successful call
        $obj = new HelloVendorUser($fxt);
        $rsp = $obj->callApiWithParams(array(
            'hello' => 'world'
        ));
        $this->assertTrue(isset($rsp['hello']));
        $this->assertEquals('world', $rsp['hello']);
    }
}
