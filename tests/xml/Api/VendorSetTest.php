<?php
 
use MyAllocator\phpsdk\Api\VendorSet;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
 
class VendorSetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new VendorSet();
        $this->assertEquals('MyAllocator\phpsdk\Api\VendorSet', get_class($obj));
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

        $obj = new VendorSet($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <VendorSet>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                    </Auth>
                    <Callback>
                        <URL>http://www.fun.com/myApiReceiver</URL>
                        <Password>password</Password>
                    </Callback>
                </VendorSet>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
