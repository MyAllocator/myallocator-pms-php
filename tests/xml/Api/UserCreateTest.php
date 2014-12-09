<?php
 
use MyAllocator\phpsdk\Api\UserCreate;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
 
class UserCreateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new UserCreate();
        $this->assertEquals('MyAllocator\phpsdk\Api\UserCreate', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
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
     * @dataProvider fixtureAuthCfgObject
     */
    public function testCallApi(array $fxt)
    {
        if (!$fxt['from_env']) {
            $this->markTestSkipped('Environment credentials not set.');
        }

        $obj = new UserCreate($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <UserCreate>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                    </Auth>
                    <UserId>phpsdkuser8</UserId>
                    <UserPassword>asfjksjkfsd</UserPassword>
                    <Email>phpsdkuser8@phpsdk.com</Email>
                    <CustomerEmail>phpsdkuser8@phpsdk.com</CustomerEmail>
                    <CustomerFirstName>Bob</CustomerFirstName>
                    <CustomerLastName>Smith</CustomerLastName>
                    <SendWelcome>0</SendWelcome>
                </UserCreate>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
