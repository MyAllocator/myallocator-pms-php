<?php
 
use MyAllocator\phpsdk\Api\LoopBookingAction;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class LoopBookingActionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new LoopBookingAction();
        $this->assertEquals('MyAllocator\phpsdk\Api\LoopBookingAction', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::get_auth_env(array(
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
        print_r($fxt);
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new LoopBookingAction($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $id = preg_replace("/[\/=+]/", "", base64_encode(openssl_random_pseudo_bytes(8)));

        // Cancel a booking
        /*
        $data = array(
            'OrderId' => '19583A964AAD-15A8-4E11-86B7-A258BA6C',
            'Actions' => array(
                'CANCEL?reason=becausethatswhy'
            ),
        );
        */

        // Uncancel a booking
        /*
        $data = array(
            'OrderId' => '19583A964AAD-15A8-4E11-86B7-A258BA6C',
            'Actions' => array(
                'UNCANCEL?reason=becausethatswrongggg'
            ),
        );
        */

        $rsp = $obj->callApiWithParams($data);
        $this->assertTrue(isset($rsp['RoomTypes']));
    }
}
