<?php

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\RoomCreate;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
 
class RoomCreateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new RoomCreate();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\RoomCreate', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword',
            'userToken',
            //'userId',
            //'userPassword',
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
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No optional args should fail (at least 1 required)
        $caught = false;
        try {
            $rsp = $obj->callApiWithParams(array());
        } catch (\exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // Create single room type 
        $data = array(
            'Rooms' => array(
                array(
                    'PMSRoomId' => '310',
                    'Label' => 'King',
                    'Units' => '10',
                    'Occupancy' => '2',
                    'Gender' => 'MI',
                    'PrivateRoom' => 'true'
                )
            )
        );
        $rsp = $obj->callApiWithParams($data);

        $this->assertTrue(isset($rsp['response']['Success']));
        $this->assertEquals($rsp['response']['Success'], 'true');
        $this->assertTrue(isset($rsp['response']['Rooms']));

        // Create multiple room types
        $data = array(
            'Rooms' => array(
                array(
                    'PMSRoomId' => '306',
                    'Label' => 'Suite G',
                    'Units' => '8',
                    'Occupancy' => '1',
                    'Gender' => 'FE',
                    'PrivateRoom' => 'false'
                ),
                array(
                    'PMSRoomId' => '307',
                    'Label' => 'Suite H',
                    'Units' => '10',
                    'Occupancy' => '2',
                    'Gender' => 'MI',
                    'PrivateRoom' => 'false'
                )
            )
        );
        $rsp = $obj->callApiWithParams($data);

        $this->assertTrue(isset($rsp['response']['Success']));
        $this->assertEquals($rsp['response']['Success'], 'true');
        $this->assertTrue(isset($rsp['response']['Rooms']));
    }
}
