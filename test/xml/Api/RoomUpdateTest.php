<?php
 
use MyAllocator\phpsdk\Api\RoomUpdate;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
 
class RoomUpdateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new RoomUpdate();
        $this->assertEquals('MyAllocator\phpsdk\Api\RoomUpdate', get_class($obj));
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
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new RoomUpdate($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $auth = $fxt['auth'];
        $xml = "
            <RoomUpdate>
                <Auth>
                    <VendorId>{$auth->vendorId}</VendorId>
                    <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                    <UserToken>{$auth->userToken}</UserToken>
                    <PropertyId>{$auth->propertyId}</PropertyId>
                </Auth>
                <UpdateRooms>
                    <RoomTypes>
                        <RoomType>
                            <RoomId>23655</RoomId>
                            <Label>SuiteY</Label>
                            <Units>5</Units>
                            <Occupancy>2</Occupancy>
                            <Gender>MI</Gender>
                            <PrivateRoom>true</PrivateRoom>
                        </RoomType>
                    </RoomTypes>
                </UpdateRooms>
            </RoomUpdate>
        ";
        $xml = str_replace(" ", "", $xml);
        $xml = str_replace("\n", "", $xml);

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
