<?php
 
use MyAllocator\phpsdk\Api\ARIUpdate;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class ARIUpdateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new ARIUpdate();
        $this->assertEquals('MyAllocator\phpsdk\Api\ARIUpdate', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword',
            //'userId',
            //'userPassword',
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

        $obj = new ARIUpdate($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $data = array(
            'Options' => array(
                'QueryForStatus' => 'true',
                //'loop_delay' => 60
            ),
            'Channels' => array(
                'loop',
                'boo'
            ),
            'Allocations' => array(
                array(
                    'RoomId' => '23651',
                    'StartDate' => '2014-12-01',
                    'EndDate' => '2014-12-30',
                    'Units' => '2',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '30.00',
                    'Price-Weekend' => '40.00'
                ),
                array(
                    'RoomId' => '23651',
                    'StartDate' => '2014-12-10',
                    'EndDate' => '2014-12-10',
                    'Units' => '2',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '1.00',
                    'Price-Weekend' => '2.00'
                ),
                array(
                    'RoomId' => '22905',
                    'StartDate' => '2014-12-11',
                    'EndDate' => '2014-12-11',
                    'Units' => '2',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '3.00',
                    'Price-Weekend' => '4.00'
                )
            )
        );

        $rsp = $obj->callApiWithParams($data);
        $this->assertTrue(isset($rsp['result']['Success']));
        $this->assertEquals('true', $rsp['result']['Success']);
    }
}
