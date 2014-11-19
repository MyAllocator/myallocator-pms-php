<?php
 
use MyAllocator\phpsdk\Api\SetRoomTypes;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
 
class SetRoomTypesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new SetRoomTypes();
        $this->assertEquals('MyAllocator\phpsdk\Api\SetRoomTypes', get_class($obj));
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
    public function testCreateRooms(array $fxt)
    {
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new SetRoomTypes($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No optional args should fail (at least 1 required)
        $caught = false;
        try {
            $rsp = $obj->createRooms(array());
        } catch (exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // Create single room type 
        $data = array(
            'Rooms' => array(
                array(
                    'Label' => 'King',
                    'Units' => '10',
                    'Occupancy' => '2',
                    'Gender' => 'MI',
                    'PrivateRoom' => 'false'
                )
            )
        );
        $rsp = $obj->createRooms($data);

        print_r($rsp);
        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'], 'true');
        $this->assertTrue(isset($rsp['RoomTypeIds']));

        // Create single room type 
        $data = array(
            'Rooms' => array(
                array(
                    'Label' => 'Queen',
                    'Units' => '10',
                    'Occupancy' => '2',
                    'Gender' => 'MI',
                    'PrivateRoom' => 'false'
                ),
                array(
                    'Label' => 'Twin',
                    'Units' => '10',
                    'Occupancy' => '2',
                    'Gender' => 'MI',
                    'PrivateRoom' => 'false'
                )
            )
        );
        $rsp = $obj->createRooms($data);

        print_r($rsp);
        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'], 'true');
        $this->assertTrue(isset($rsp['RoomTypeIds']));
    }
}
