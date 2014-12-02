<?php
 
use MyAllocator\phpsdk\Api\PropertyModify;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
 
class PropertyModifyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new PropertyModify();
        $this->assertEquals('MyAllocator\phpsdk\Api\PropertyModify', get_class($obj));
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
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new PropertyModify($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Successful call
        $rsp = $obj->callApiWithParams(array(
            'PropertyName' => 'PHP SDK Hotel AA',
            'ExpiryDate' => '2015-01-20',
            'Currency' => 'USD',
            'Country' => 'US',
            'Breakfast' => 'EX'
        ));

        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'], 'true');
    }
}
