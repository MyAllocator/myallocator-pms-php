<?php

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\ARIUpdateStatus;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
use MyAllocator\phpsdk\src\Exception\ApiException;
 
class ARIUpdateStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new ARIUpdateStatus();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\ARIUpdateStatus', get_class($obj));
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
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No required parameters should throw exception
        $caught = false;
        try {
            $rsp = $obj->callApi();
        } catch (\exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

/*
        // Invalid update id should fail
        $rsp = $obj->callApiWithParams(array(
            'UpdateId' => '123999999999'
        ));
        print_r($rsp);
        $this->assertTrue(isset($rsp['response']['Errors']));
        $this->assertEquals($rsp['response']['Errors'][0]['ErrorMsg'], 'No such booking id');
*/

        // Successful call
        $rsp = $obj->callApiWithParams(array(
            'UpdateId' => '3393737'
        ));
        $this->assertTrue(isset($rsp['response']['Channels']));
    }
}
