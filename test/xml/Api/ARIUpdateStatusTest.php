<?php
 
use MyAllocator\phpsdk\Api\ARIUpdateStatus;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class ARIUpdateStatusTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new ARIUpdateStatus();
        $this->assertEquals('MyAllocator\phpsdk\Api\ARIUpdateStatus', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
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

        $obj = new ARIUpdateStatus($fxt);

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
/*
        $rsp = $obj->callApiWithParams(array(
            'UpdateId' => '123'
        ));
        print_r($rsp);
        $this->assertTrue(isset($rsp['Errors']));
        $this->assertEquals($rsp['Errors'][0]['ErrorMsg'], 'No such booking id');
*/

        // Successful call
        $rsp = $obj->callApiWithParams(array(
            'UpdateId' => '2866102'
        ));
        print_r($rsp);
        $this->assertTrue(isset($rsp['Errors']));
        $this->assertEquals($rsp['Errors'][0]['ErrorMsg'], 'No such booking id');
    }
}
