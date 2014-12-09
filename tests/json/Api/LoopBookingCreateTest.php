<?php

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\LoopBookingCreate;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
 
class LoopBookingCreateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new LoopBookingCreate();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\LoopBookingCreate', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword',
            'userToken',
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
        print_r($fxt);
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new LoopBookingCreate($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Create a booking
        $data = array(
            'Booking' => array(
                'StartDate' => '2014-12-10',
                'EndDate' => '2014-12-13',
                'Units' => '1',
                'RoomTypeId' => '23651',
                'RateId' => '123',
                'RoomDayRate' => '100.00',
                'RoomDayDescription' => 'A fun RoomDay!',
                'CustomerFName' => 'Nathan',
                'CustomerLName' => 'Meeper',
                'RoomDesc' => 'A fun RoomDesc!',
                'OccupantSmoker' => 'true',
                'OccupantNote' => 'Please do not put me by the elevator. Thanks!',
                'OccupantFName' => 'Nathan',
                'OccupantLName' => 'Meeper',
                'Occupancy' => '1',
                'Policy' => 'No smoking.',
                'ChannelRoomType' => '123'
            )
        );

        $rsp = $obj->callApiWithParams($data);
        $this->assertTrue(isset($rsp['response']['Booking']));
    }
}
