<?php

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\UserExists;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
 
class UserExistsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new UserExists();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\UserExists', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
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
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Exists by email (Omitting required UserId parameter)
        $caught = false;
        try {
            $rsp = $obj->callApiWithParams(array(
                'CustomerEmail' => 'phpsdkuser@phpsdk.com'
            ));
        } catch (\exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }
         
        // Exists by id
        $rsp = $obj->callApiWithParams(array(
            'UserId' => 'phpsdkuser'
        ));
        $this->assertTrue(isset($rsp['response']['EmailExists']));
        $this->assertTrue(isset($rsp['response']['UserIdExists']));

        // Exists by id and email
        $rsp = $obj->callApiWithParams(array(
            'UserId' => 'phpsdkuser',
            'CustomerEmail' => 'phpsdkuser@phpsdk.com'
        ));
        $this->assertTrue(isset($rsp['response']['EmailExists']));
        $this->assertTrue(isset($rsp['response']['UserIdExists']));
    }
}
