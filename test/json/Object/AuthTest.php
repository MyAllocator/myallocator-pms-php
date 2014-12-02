<?php
 
use MyAllocator\phpsdk\Object\Auth;
 
class AuthTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new Auth();
        $this->assertEquals('MyAllocator\phpsdk\Object\Auth', get_class($obj));
    }

    public function fixtureObjectProperties()
    {
        $data = array();
        $data[] = array(array(
            'vendorId' => '777',
            'vendorPassword' => '888',
            'userId' => '999',
            'userPassword' => '1010',
            'propertyId' => '1111',
            'PMSPropertyId' => '1212',
            'debug' => true
        ));

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @group object
     * @dataProvider fixtureObjectProperties
     */
    public function testObjectProperties(array $fxt)
    {
        $auth = new Auth();
        $auth->vendorId = $fxt['vendorId'];
        $auth->vendorPassword = $fxt['vendorPassword'];
        $auth->userId = $fxt['userId'];
        $auth->userPassword = $fxt['userPassword'];
        $auth->propertyId = $fxt['propertyId'];
        $auth->PMSPropertyId = $fxt['PMSPropertyId'];
        $auth->debug = $fxt['debug'];

        $this->assertEquals($auth->vendorId, $fxt['vendorId']);
        $this->assertEquals($auth->vendorPassword, $fxt['vendorPassword']);
        $this->assertEquals($auth->userId, $fxt['userId']);
        $this->assertEquals($auth->userPassword, $fxt['userPassword']);
        $this->assertEquals($auth->propertyId, $fxt['propertyId']);
        $this->assertEquals($auth->PMSPropertyId, $fxt['PMSPropertyId']);
        $this->assertEquals($auth->debug, $fxt['debug']);
    }

    /**
     * @author nathanhelenihi
     * @group api
     * @dataProvider fixtureObjectProperties
     */
    public function testGetAuthKeyVar(array $fxt)
    {
        $auth = new Auth();
        $auth->vendorId = $fxt['vendorId'];
        $auth->vendorPassword = $fxt['vendorPassword'];
        $auth->userId = $fxt['userId'];
        $auth->userPassword = $fxt['userPassword'];
        $auth->propertyId = $fxt['propertyId'];
        $auth->PMSPropertyId = $fxt['PMSPropertyId'];
        $auth->debug = $fxt['debug'];

        // Test valid authentication keys
        $this->assertEquals($auth->vendorId, $auth->getAuthKeyVar('Auth/VendorId'));
        $this->assertEquals($auth->vendorPassword, $auth->getAuthKeyVar('Auth/VendorPassword'));
        $this->assertEquals($auth->userId, $auth->getAuthKeyVar('Auth/UserId'));
        $this->assertEquals($auth->userPassword, $auth->getAuthKeyVar('Auth/UserPassword'));
        $this->assertEquals($auth->propertyId, $auth->getAuthKeyVar('Auth/PropertyId'));
        $this->assertEquals($auth->PMSPropertyId, $auth->getAuthKeyVar('PMSPropertyId'));

        // Test invalid key
        try {
            $auth->getAuthKeyVar('invalid_key');
        } catch (Exception $e) {
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        // Test api with no auth set
        unset($auth);
        $auth = new Auth();
        $this->assertEquals(null, $auth->getAuthKeyVar('Auth/VendorId'));
    }
}
