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
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new PropertyModify($fxt);
        $obj->setConfig('dataFormat', 'xml');
    
        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $auth = $fxt['auth'];
        $xml = "
            <PropertyCreate>
                <Auth>
                    <VendorId>{$auth->vendorId}</VendorId>
                    <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                    <UserToken>{$auth->userToken}</UserToken>
                    <PropertyId>4547</PropertyId>
                </Auth>
                <PropertyName>PHP SDK Hotel H</PropertyName>
                <ExpiryDate>2014-12-20</ExpiryDate>
                <Currency>USD</Currency>
                <Country>US</Country>
                <Breakfast>EX</Breakfast>
            </PropertyCreate>
        ";
        $xml = str_replace(" ", "", $xml);
        $xml = str_replace("\n", "", $xml);

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
