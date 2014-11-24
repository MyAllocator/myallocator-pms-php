<?php
 
use MyAllocator\phpsdk\Api\AssociateUserToPMS;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class AssociateUserToPMSTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new AssociateUserToPMS();
        $this->assertEquals('MyAllocator\phpsdk\Api\AssociateUserToPMS', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::get_auth_env(array(
            'vendorId',
            'vendorPassword',
            'userId',
            'userPassword'
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

        $obj = new AssociateUserToPMS($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $rsp = $obj->callApi();
        print_r($rsp);
        $this->assertTrue(isset($rsp['Success']));
    }
}
