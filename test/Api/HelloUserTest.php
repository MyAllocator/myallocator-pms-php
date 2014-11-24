<?php
 
use MyAllocator\phpsdk\Api\HelloUser;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class HelloUserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new HelloUser();
        $this->assertEquals('MyAllocator\phpsdk\Api\HelloUser', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::get_auth_env(array(
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
        $obj = new HelloUser();
        try {
            $rsp = $obj->callApiWithParams(array(
                'hello' => 'world'
            ));
        } catch (Exception $e) {
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiAuthenticationException', $e);
        }

        // Auth invalid
        $fxt['auth']->userId = '111';
        $fxt['auth']->userPassword = '111';
        $obj = new HelloUser($fxt);
        $rsp = $obj->callApiWithParams(array(
            'hello' => 'world'
        ));
        $this->assertTrue(isset($rsp['Errors']));
        $this->assertTrue(isset($rsp['Errors'][0]['ErrorMsg']));
        $this->assertEquals('Invalid user or user password', $rsp['Errors'][0]['ErrorMsg']);

        // Successful call
        $obj = new HelloUser($fxt);
        $rsp = $obj->callApiWithParams(array(
            'hello' => 'world'
        ));
        $this->assertTrue(isset($rsp['hello']));
        $this->assertEquals('world', $rsp['hello']);
    }
}
