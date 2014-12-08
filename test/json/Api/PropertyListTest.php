<?php
 
use MyAllocator\phpsdk\Api\PropertyList;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class PropertyListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new PropertyList();
        $this->assertEquals('MyAllocator\phpsdk\Api\PropertyList', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword',
            'userId',
            'userPassword'
        ));
        $data = array();
        $data[] = array($auth);

        return $data;
    }

    public function fixtureAuthCfgObjectProperty()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword',
            'userId',
            'userPassword',
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

        // Get information about all properties associated with a user/vendor.
        $obj = new PropertyList($fxt);
        $obj->setConfig('dataFormat', 'array');
        $rsp = $obj->callApi();
        $this->assertTrue(isset($rsp['response']['Properties']));
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureAuthCfgObjectProperty
     */
    public function testCallApiProperty(array $fxt)
    {
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        // Get information about a specific property.
        $obj = new PropertyList($fxt);
        $obj->setConfig('dataFormat', 'array');
        $rsp = $obj->callApi();
        $this->assertTrue(isset($rsp['response']['Properties']));
    }
}
