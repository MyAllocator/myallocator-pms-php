<?php
 
use MyAllocator\phpsdk\Api\PropertyCreate;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
 
class PropertyCreateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new PropertyCreate();
        $this->assertEquals('MyAllocator\phpsdk\Api\PropertyCreate', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword',
            'userToken'
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

        $obj = new PropertyCreate($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Successful call
        $rsp = $obj->callApiWithParams(array(
            'PropertyName' => 'PHP SDK Hotel G',
            'ExpiryDate' => '2014-12-20',
            'Currency' => 'USD',
            'Country' => 'US',
            'Breakfast' => 'EX'
        ));

        $this->assertTrue(isset($rsp['response']['Success']));
        $this->assertEquals($rsp['response']['Success'], 'true');
    }
}
