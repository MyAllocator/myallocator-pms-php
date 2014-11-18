<?php
 
use MyAllocator\phpsdk\Api\HelloUserVendor;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class HelloUserVendorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new HelloUserVendor();
        $this->assertEquals('MyAllocator\phpsdk\Api\HelloUserVendor', get_class($obj));
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
    public function testSayHello(array $fxt)
    {
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new HelloUserVendor($fxt);
        $rsp = $obj->sayHello();
        $this->assertTrue(isset($rsp['hello']));
        $this->assertEquals('world', $rsp['hello']);
    }

    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testSayHelloAuthNull()
    {
        $obj = new HelloUserVendor();
        try {
            $rsp = $obj->sayHello();
        } catch (Exception $e) {
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiAuthenticationException', $e);
        }
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureAuthCfgObject
     */
    public function testSayHelloAuthInvalid(array $fxt)
    {
        $fxt['auth']->vendorId = '111';
        $fxt['auth']->vendorPassword = '111';
        $fxt['auth']->userId = '111';
        $fxt['auth']->userPassword = '111';
        $obj = new HelloUserVendor($fxt);
        $rsp = $obj->sayHello();
        $this->assertTrue(isset($rsp['Errors']));
        $this->assertTrue(isset($rsp['Errors'][0]['ErrorMsg']));
        $this->assertEquals('Invalid vendor or vendor password', $rsp['Errors'][0]['ErrorMsg']);
    }
}
