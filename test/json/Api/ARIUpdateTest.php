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
        $auth = Common::get_auth_env(array(
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

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $id = preg_replace("/[\/=+]/", "", base64_encode(openssl_random_pseudo_bytes(8)));

/*
        $data = array(
            'UpdatesId' => $id,
            'Options' => array(
                'QueryForStatus' => 'true'
            ),
            'Channels' => array(
                //'exp',
                //'boo'
                //'loop',
                //'boo'
                'all'
            ),
            'Allocations' => array(
                array(
                    'RoomId' => '22797',
                    'StartDate' => '2014-12-01',
                    'EndDate' => '2014-12-20',
                    'Units' => '5',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '100.00',
                    'Price-Weekend' => '200.00'
                ),
                array(
                    'RoomId' => '22797',
                    'StartDate' => '2014-12-21',
                    'EndDate' => '2014-12-30',
                    'Units' => '1',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '300.00',
                    'Price-Weekend' => '400.00'
                ),
                array(
                    'RoomId' => '22905',
                    'StartDate' => '2014-12-01',
                    'EndDate' => '2014-12-20',
                    'Units' => '3',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '500.00',
                    'Price-Weekend' => '600.00'
                ),
                array(
                    'RoomId' => '22905',
                    'StartDate' => '2014-12-21',
                    'EndDate' => '2014-12-30',
                    'Units' => '2',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '700.00',
                    'Price-Weekend' => '800.00'
                )
            )
        );
*/

        $data = array(
            'UpdatesId' => $id,
            'Options' => array(
                'QueryForStatus' => 'true',
                'loop_delay' => 60
            ),
            'Channels' => array(
                //'exp',
                //'boo'
                'loop',
                'boo'
            ),
            'Allocations' => array(
                array(
                    'RoomId' => '22797',
                    'StartDate' => '2014-12-01',
                    'EndDate' => '2014-12-30',
                    'Units' => '2',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '100.00',
                    'Price-Weekend' => '200.00'
                ),
                array(
                    'RoomId' => '22797',
                    'StartDate' => '2014-12-10',
                    'EndDate' => '2014-12-10',
                    'Units' => '2',
                    'MinStay' => '1',
                    'MaxStay' => '3',
                    'Price' => '1.00',
                    'Price-Weekend' => '2.00'
                ),
                array(
                    'RoomId' => '22797',
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
        $this->assertTrue(isset($rsp['RoomTypes']));

        // TODO add structure tests once JSON response fixed
    }
}
