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
        $obj->setConfig('dataFormat', 'array');

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
                'RoomId' => '23649',
                'Units' => '3',
                'PrivateRoom' => 'true',
                'Gender' => 'MA',
                'Occupancy' => '2',
                'Label' => 'Suite G'
            )
        );
        $rsp = $obj->callApiWithParams($data);

        $this->assertTrue(isset($rsp['response']['Success']));
        $this->assertEquals($rsp['response']['Success'], 'true');

        // Update multiple room type 
        $data = array(
            'Rooms' => array(
                array(
                    'RoomId' => '23650',
                    'Label' => 'Suite H'
                ),
                array(
                    'RoomId' => '22797',
                    'Label' => 'King'
                )
            )
        );
        $rsp = $obj->callApiWithParams($data);

        $this->assertTrue(isset($rsp['response']['Success']));
        $this->assertEquals($rsp['response']['Success'], 'true');
    }
}
