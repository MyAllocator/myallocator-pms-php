<?php
 
use MyAllocator\phpsdk\Api\UserExists;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
 
class UserExistsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new UserExists();
        $this->assertEquals('MyAllocator\phpsdk\Api\UserExists', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::get_auth_env(array(
            'vendorId',
            'vendorPassword',
            'userToken'
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

        $obj = new UserExists($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Exists by email (Omitting required UserId parameter)
        $caught = false;
        try {
            $rsp = $obj->callApiWithParams(array(
                'Email' => 'phpsdkuser@phpsdk.com'
            ));
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }
         
        // Exists by id
        $rsp = $obj->callApiWithParams(array(
            'UserId' => 'phpsdkuser'
        ));
        $this->assertTrue(isset($rsp['EmailExists']));
        $this->assertTrue(isset($rsp['UserIdExists']));

        // Exists by id and email
        $rsp = $obj->callApiWithParams(array(
            'UserId' => 'phpsdkuser',
            'Email' => 'phpsdkuser@phpsdk.com'
        ));
        $this->assertTrue(isset($rsp['EmailExists']));
        $this->assertTrue(isset($rsp['UserIdExists']));
    }
}
