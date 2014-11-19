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
        $auth = Common::get_auth_env(array(
            'vendorId',
            'vendorPassword'
        ));
        $data = array();
        $data[] = array($auth);

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @group skip
     * @dataProvider fixtureAuthCfgObject
     */
    public function testCreate(array $fxt)
    {
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        if (!PROPERTY_CREATE_ENABLED) {
            $this->markTestSkipped('PropertyCreate API disabled!');
        }

        $obj = new PropertyCreate($fxt);

        // Null params should fail
        $caught = false;
        try {
            $obj->create();
        } catch (Exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('Should have thrown an exception');
        }

        // Empty array should fail
        $caught = false;
        try {
            $obj->create(array());
        } catch (exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // No user id should fail
        $caught = false;
        try {
            $rsp = $obj->create(array(
                'PropertyName' => 'PHP SDK Hotel A',
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

        // Successful call
        $rsp = $obj->create(array(
            'UserId' => 'phpsdk',
            'PropertyName' => 'PHP SDK Hotel A',
            'ExpiryDate' => '2020-01-01',
            'Currency' => 'USD',
            'Country' => 'US',
            'Breakfast' => 'EX'
        ));

        var_dump($rsp);
        $this->assertTrue(isset($rsp['Success']));
        $this->assertEquals($rsp['Success'][0], 'true');
    }
}
