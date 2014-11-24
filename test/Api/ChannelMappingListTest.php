<?php
 
use MyAllocator\phpsdk\Api\ChannelMappingList;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class ChannelMappingListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new ChannelMappingList();
        $this->assertEquals('MyAllocator\phpsdk\Api\ChannelMappingList', get_class($obj));
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
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        print_r($fxt);

        $obj = new ChannelMappingList($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $rsp = $obj->callApi();
        print_r($rsp);
        $this->assertTrue(isset($rsp['Properties']));
    }
}
