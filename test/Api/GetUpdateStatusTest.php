<?php
 
use MyAllocator\phpsdk\Api\GetUpdateStatus;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class GetUpdateStatusTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new GetUpdateStatus();
        $this->assertEquals('MyAllocator\phpsdk\Api\GetUpdateStatus', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::get_auth_env(array(
            'vendorId',
            'vendorPassword',
            'userId',
            'userPassword',
            'propertyId'
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

        $obj = new GetUpdateStatus($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No required parameters should throw exception
        $caught = false;
        try {
            $rsp = $obj->callApi();
        } catch (exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // Invalid update id should fail
        $rsp = $obj->callApiWithParams(array(
            'UpdateId' => '123'
        ));
        print_r($rsp);
        $this->assertTrue(isset($rsp['Errors']));
        $this->assertEquals($rsp['Errors'][0]['ErrorMsg'], 'No such booking id');
    }
}
