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

        $data = array(
            'UpdatesId' => $id,
            'Options' => array(
                'QueryForStatus' => 'true'
            ),
            'Updates' => array(
                array(
                    'Channels' => array(
                        'all'
                        //'boo',
                        //'loc'
                    ),
                    'Allocations' => array(
                        array(
                            'RoomId' => '22797',
                            'StartDate' => '2014-12-01',
                            'EndDate' => '2014-12-20',
                            'Units' => '5',
                            'MinStay' => '1',
                            'MaxStay' => '3',
                            'Price' => '97.00',
                            //'Price-Weekday' => '80.00',
                            'Price-Weekend' => '107.00'
                        ),
                        array(
                            'RoomId' => '22797',
                            'StartDate' => '2014-12-21',
                            'EndDate' => '2014-12-30',
                            'Units' => '5',
                            'MinStay' => '1',
                            'MaxStay' => '3',
                            'Price' => '166.00',
                            //'Price-Weekday' => '150.00',
                            'Price-Weekend' => '206.00'
                        )
                    )
                )
            )
        );

        /*
        $data = array(
            'UpdatesId' => $id,
            'Updates' => array(
                array(
                    'Channels' => array(
                        'boo',
                        'exp'
                    ),
                    'Allocations' => array(
                        array(
                            'RoomId' => '59',
                            'StartDate' => '2014-12-01',
                            'EndDate' => '2014-12-20',
                            'Units' => '5',
                            'MinStay' => '1',
                            'MaxStay' => '3',
                            'Price' => '80.00',
                            'Price-Weekday' => '80.00',
                            'Price-Weekend' => '100.00'
                        ),
                        array(
                            'RoomId' => '59',
                            'StartDate' => '2014-12-21',
                            'EndDate' => '2014-12-30',
                            'Units' => '5',
                            'MinStay' => '1',
                            'MaxStay' => '3',
                            'Price' => '150.00',
                            'Price-Weekday' => '150.00',
                            'Price-Weekend' => '200.00'
                        )
                    )
                ),
                array(
                    'Channels' => array(
                        'hw2',
                        'boo'
                    ),
                    'Allocations' => array(
                        array(
                            'RoomId' => '59',
                            'StartDate' => '2014-12-01',
                            'EndDate' => '2014-12-20',
                            'Units' => '5',
                            'MinStay' => '1',
                            'MaxStay' => '3',
                            'Price' => '80.00',
                            'Price-Weekday' => '80.00',
                            'Price-Weekend' => '100.00'
                        )
                    )
                ),
                array(
                    'Channels' => array(
                        'all'
                    ),
                    'Allocations' => array(
                        array(
                            'RoomId' => '59',
                            'StartDate' => '2014-12-01',
                            'EndDate' => '2014-12-20',
                            'Units' => '5',
                            'MinStay' => '1',
                            'MaxStay' => '3',
                            'Price' => '80.00',
                            'Price-Weekday' => '80.00',
                            'Price-Weekend' => '100.00'
                        )
                    )
                )
            )
        );
        */

        $rsp = $obj->callApiWithParams($data);
        $this->assertTrue(isset($rsp['RoomTypes']));

        // TODO add structure tests once JSON response fixed
    }
}
