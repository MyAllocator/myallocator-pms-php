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
            'propertyIdMyAllocator' => '1111',
            'propertyIdSystem' => '1212',
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
        $auth->propertyIdMyAllocator = $fxt['propertyIdMyAllocator'];
        $auth->propertyIdSystem = $fxt['propertyIdSystem'];
        $auth->debug = $fxt['debug'];

        $this->assertEquals($auth->vendorId, $fxt['vendorId']);
        $this->assertEquals($auth->vendorPassword, $fxt['vendorPassword']);
        $this->assertEquals($auth->userId, $fxt['userId']);
        $this->assertEquals($auth->userPassword, $fxt['userPassword']);
        $this->assertEquals($auth->propertyIdMyAllocator, $fxt['propertyIdMyAllocator']);
        $this->assertEquals($auth->propertyIdSystem, $fxt['propertyIdSystem']);
        $this->assertEquals($auth->debug, $fxt['debug']);
    }
}
