<?php

namespace MyAllocator\phpsdk\tests\xml;
 
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

        $obj = new LoopBookingCreate($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <LoopBookingCreate>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                        <UserId>{$auth->userId}</UserId>
                        <UserPassword>{$auth->userPassword}</UserPassword>
                        <PropertyId>{$auth->propertyId}</PropertyId>
                    </Auth>
                    <StartDate>2014-12-12</StartDate>
                    <EndDate>2014-12-14</EndDate>
                    <Units>1</Units>
                    <RoomTypeId>23651</RoomTypeId>
                    <RateId>123</RateId>
                    <RoomDayRate>100.00</RoomDayRate>
                    <RoomDayDescription>A description</RoomDayDescription>
                    <CustomerFName>Frank</CustomerFName>
                    <CustomerLName>Blue</CustomerLName>
                    <RoomDesc>A description</RoomDesc>
                    <OccupantSmoker>false</OccupantSmoker>
                    <OccupantNote>Please not by elevator!</OccupantNote>
                    <OccupantFName>Frank</OccupantFName>
                    <OccupantLName>Blue</OccupantLName>
                    <Occupancy>1</Occupancy>
                    <Policy>No smoking.</Policy>
                    <ChannelRoomType>123</ChannelRoomType>
                </LoopBookingCreate>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
