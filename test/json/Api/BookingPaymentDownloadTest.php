<?php
 
use MyAllocator\phpsdk\Api\BookingPaymentDownload;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class BookingPaymentDownloadTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new BookingPaymentDownload();
        $this->assertEquals('MyAllocator\phpsdk\Api\BookingPaymentDownload', get_class($obj));
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

        $obj = new BookingPaymentDownload($fxt);

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

        // Invalid booking id should fail
        $rsp = $obj->callApiWithParams(array(
            'OrderId' => '99999999999999999',
            'CreditCardPassword' => '123'
        ));
        $this->assertTrue(isset($rsp['response']['Errors']));
        $this->assertEquals(
            $rsp['response']['Errors'][0]['ErrorMsg'],
            'No such booking id'
        );

/*
        // Valid booking id and invalid password should fail
        $rsp = $obj->callApiWithParams(array(
            'OrderId' => '4304-63761582-4625',
            'CreditCardPassword' => '123'
        ));
        $this->assertTrue(isset($rsp['response']['Errors']));
        $this->assertEquals(
            $rsp['response']['Errors'][0]['ErrorMsg'],
            'No such booking id'
        );
*/

        // Valid order id and valid password should succeed
        $rsp = $obj->callApiWithParams(array(
            'OrderId' => '4304-63761582-4625',
            'CreditCardPassword' => '!password1'
        ));
        $this->assertTrue(isset($rsp['response']['Payments']));

        // Valid myallocator`` id and valid password should succeed
        $rsp = $obj->callApiWithParams(array(
            'MyAllocatorId' => '5485e70e399dbd9a2451a744',
            'CreditCardPassword' => '!password1'
        ));
        $this->assertTrue(isset($rsp['response']['Payments']));
    }
}
