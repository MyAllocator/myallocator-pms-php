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
            'userId',
            'userPassword',
            //'userToken'
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

        // No user id / password should fail
        $caught = false;
        try {
            $rsp = $obj->callApiWithParams(array(
                'PropertyName' => 'PHP SDK Hotel B',
                'ExpiryDate' => '2020-01-01',
                'Currency' => 'USD',
                'Country' => 'US',
                'Breakfast' => 'EX'
            ));
        } catch (exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        /*
         * Successful calls require special vendor permissions.

        // Successful call
        $rsp = $obj->callApiWithParams(array(
            'UserId' => 'phpsdk_property_A',
            'UserPassword' => 'password', // update to real password
            'PropertyName' => 'PHP SDK Hotel A',
            'ExpiryDate' => '2020-01-01',
            'Currency' => 'USD',
            'Country' => 'US',
            'Breakfast' => 'EX'
        ));

        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'][0], 'true');

        */
    }
}
