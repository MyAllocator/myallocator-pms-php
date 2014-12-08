<?php
 
use MyAllocator\phpsdk\Api\BookingList;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class BookingListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new BookingList();
        $this->assertEquals('MyAllocator\phpsdk\Api\BookingList', get_class($obj));
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

        $obj = new BookingList($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No optional parameters should throw exception
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

        // Arrival parameters
        $rsp = $obj->callApiWithParams(array(
            'ArrivalStartDate' => '2014-11-01',
            'ArrivalEndDate' => '2014-12-30'
        ));
        $this->assertTrue(isset($rsp['response']['Bookings']));

        // Modification parameters
        $rsp = $obj->callApiWithParams(array(
            'ModificationStartDate' => '2014-11-01',
            'ModificationEndDate' => '2014-12-30'
        ));
        $this->assertTrue(isset($rsp['response']['Bookings']));

        // Creation parameters
        $rsp = $obj->callApiWithParams(array(
            'CreationStartDate' => '2014-11-01',
            'CreationEndDate' => '2014-12-30'
        ));
        $this->assertTrue(isset($rsp['response']['Bookings']));
    }
}
