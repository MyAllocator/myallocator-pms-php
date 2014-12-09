<?php
 
use MyAllocator\phpsdk\Api\AssociatePropertyToPMS;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class AssociatePropertyToPMSTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new AssociatePropertyToPMS();
        $this->assertEquals('MyAllocator\phpsdk\Api\AssociatePropertyToPMS', get_class($obj));
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

        $obj = new AssociatePropertyToPMS($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <AssociatePropertyToPMS>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                        <UserId>{$auth->userId}</UserId>
                        <UserPassword>{$auth->userPassword}</UserPassword>
                        <PropertyId>{$auth->propertyId}</PropertyId>
                    </Auth>
                </AssociatePropertyToPMS>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
