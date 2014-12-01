<?php
 
use MyAllocator\phpsdk\Api\RoomCreate;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
 
class RoomCreateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new RoomCreate();
        $this->assertEquals('MyAllocator\phpsdk\Api\RoomCreate', get_class($obj));
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

        $obj = new RoomCreate($fxt);

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

/*
        // Create single room type 
        $data = array(
            'Rooms' => array(
                array(
                    'PMSRoomId' => '107',
                    'Label' => 'Double2',
                    'Units' => '10',
                    'Occupancy' => '2',
                    'Gender' => 'MA',
                    'PrivateRoom' => 'true'
                )
            )
        );
        $rsp = $obj->callApiWithParams($data);

        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'], 'true');
        $this->assertTrue(isset($rsp['Rooms']));
*/

        // Create single room type 
        $data = array(
            'Rooms' => array(
                array(
                    'PMSRoomId' => '110',
                    'Label' => 'Double4',
                    'Units' => '10',
                    'Occupancy' => '2',
                    'Gender' => 'FE',
                    'PrivateRoom' => 'false'
                ),
                array(
                    'PMSRoomId' => '111',
                    'Label' => 'Double5',
                    'Units' => '10',
                    'Occupancy' => '2',
                    'Gender' => 'MI',
                    'PrivateRoom' => 'false'
                )
            )
        );
        $rsp = $obj->callApiWithParams($data);

        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'], 'true');
        $this->assertTrue(isset($rsp['Rooms']));
    }
}
