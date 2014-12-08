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
        print_r($fxt);
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new LoopBookingAction($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Cancel a booking
        $data = array(
            'OrderId' => '6862C94E9DFA-42C9-4E11-D0F7-297C5F79',
            'Actions' => array(
                'CANCEL?reason=hotelierwasmean'
            ),
        );

        $rsp = $obj->callApiWithParams($data);
        $this->assertTrue(isset($rsp['response']['%Booking']));

        // Uncancel a booking
        $data = array(
            'OrderId' => '6862C94E9DFA-42C9-4E11-D0F7-297C5F79',
            'Actions' => array(
                'UNCANCEL?reason=changedmymind'
            ),
        );

        $rsp = $obj->callApiWithParams($data);
        $this->assertTrue(isset($rsp['response']['%Booking']));

        // Modify to come at a later time
    }
}
