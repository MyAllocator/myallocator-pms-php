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

        $obj = new RoomUpdate($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No optional args should fail (at least 1 required)
        $caught = false;
        try {
            $rsp = $obj->callApiWithParams(array());
        } catch (exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // Update single room type 
        $data = array(
            'Room' => array(
                'RoomId' => '22797',
                'Units' => '10',
                'PrivateRoom' => 'true',
                'PMSRoomId' => 200,
                'Gender' => 'MA',
                'Occupancy' => '2'
            )
        );
        $rsp = $obj->callApiWithParams($data);

        print_r($rsp);
        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'], 'true');

/*
        // Update multiple room type 
        $data = array(
            'Rooms' => array(
                array(
                    'RoomId' => '22797',
                    'Units' => '5',
                    'PrivateRoom' => 'false',
                    'PMSRoomId' => 200,
                    'Gender' => 'MI',
                    'Occupancy' => '5'
                ),
                array(
                    'RoomId' => '22905',
                    'Units' => '5',
                    'PrivateRoom' => 'false',
                    'PMSRoomId' => 201,
                    'Gender' => 'MI',
                    'Occupancy' => '5'
                )
            )
        );
        $rsp = $obj->callApiWithParams($data);

        print_r($rsp);
        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'], 'true');
*/
    }
}
