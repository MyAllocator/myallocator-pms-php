<?php

namespace MyAllocator\phpsdk\tests\xml;
 
use MyAllocator\phpsdk\src\Api\LoopBookingList;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
 
class LoopBookingListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new LoopBookingList();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\LoopBookingList', get_class($obj));
    }

    public function fixtureAuthCfgObject()
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

        $obj = new LoopBookingList($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Arrival Parameters
        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <LoopBookingList>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                        <UserId>{$auth->userId}</UserId>
                        <UserPassword>{$auth->userPassword}</UserPassword>
                        <PropertyId>{$auth->propertyId}</PropertyId>
                    </Auth>
                    <ArrivalStartDate>2014-12-08</ArrivalStartDate>
                    <ArrivalEndDate>2014-12-15</ArrivalEndDate>
                </LoopBookingList>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );

        // Modification Parameters
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
            <LoopBookingList>
                <Auth>
                    <VendorId>{$auth->vendorId}</VendorId>
                    <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                    <UserId>{$auth->userId}</UserId>
                    <UserPassword>{$auth->userPassword}</UserPassword>
                    <PropertyId>{$auth->propertyId}</PropertyId>
                </Auth>
                <ModificationStartDate>2014-12-07</ModificationStartDate>
                <ModificationEndDate>2014-12-15</ModificationEndDate>
            </LoopBookingList>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );

        // Arrival Parameters
        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <LoopBookingList>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                        <UserId>{$auth->userId}</UserId>
                        <UserPassword>{$auth->userPassword}</UserPassword>
                        <PropertyId>{$auth->propertyId}</PropertyId>
                    </Auth>
                    <CreationStartDate>2014-12-08</CreationStartDate>
                    <CreationEndDate>2014-12-15</CreationEndDate>
                </LoopBookingList>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
