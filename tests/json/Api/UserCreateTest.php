<?php

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\UserCreate;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
 
class UserCreateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new UserCreate();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\UserCreate', get_class($obj));
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

        $obj = new UserCreate($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No user id should fail
        $caught = false;
        try {
            $rsp = $obj->callApiWithParams(array(
                'UserPassword' => 'phpsdkpassword',
                'Email' => 'phpsdkuser@phpsdk.com',
                'CustomerEmail' => 'phpsdkcustomer@phpsdk.com'
            ));
        } catch (\exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // Successful call with optional parameters
        $rsp = $obj->callApiWithParams(array(
            'UserId' => 'phpsdkuser',
            'UserPassword' => 'asfjksjkfsd',
            'Email' => 'phpsdkuser@phpsdk.com',
            'CustomerEmail' => 'phpsdkuser@phpsdk.com',
            'CustomerFirstName' => 'Bob',
            'CustomerLastName' => 'Smith',
            'SendWelcome' => 0
        ));

        $this->assertTrue(isset($rsp['response']['UserIdExists']));
        $this->assertEquals($rsp['response']['UserIdExists'], 'false');
        $this->assertTrue(isset($rsp['response']['Success']));
        $this->assertEquals($rsp['response']['Success'], 'true');
    }
}
