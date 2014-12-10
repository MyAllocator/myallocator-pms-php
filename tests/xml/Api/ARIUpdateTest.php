<?php

namespace MyAllocator\phpsdk\tests\xml;
 
use MyAllocator\phpsdk\src\Api\ARIUpdate;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
 
class ARIUpdateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new ARIUpdate();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\ARIUpdate', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId',
            'vendorPassword',
            //'userId',
            //'userPassword',
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

        $obj = new ARIUpdate($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $auth = $fxt['auth'];

        //<loop_delay>10</loop_delay>
        $options = "
                <Options>
                    <QueryForStatus>true</QueryForStatus>
                    <loop_delay>30</loop_delay>
                    <FailIfUpdateActive>false</FailIfUpdateActive>
                </Options>
        ";

        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <ARIUpdate>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                        <UserToken>{$auth->userToken}</UserToken>
                        <PropertyId>{$auth->propertyId}</PropertyId>
                    </Auth>
                    ".$options."
                    <Channels>
                        <Channel>loop</Channel>
                        <Channel>boo</Channel>
                    </Channels>
                    <Allocations>
                        <Allocation>
                            <RoomId>23651</RoomId>
                            <StartDate>2014-12-01</StartDate>
                            <EndDate>2014-12-30</EndDate>
                            <Units>2</Units>
                            <MinStay>1</MinStay>
                            <MaxStay>3</MaxStay>
                            <Prices>
                                <Price>451.00</Price>
                                <Price weekend=\"true\">500.00</Price>
                            </Prices>
                        </Allocation>
                        <Allocation>
                            <RoomId>23651</RoomId>
                            <StartDate>2014-12-10</StartDate>
                            <EndDate>2014-12-10</EndDate>
                            <Units>2</Units>
                            <MinStay>1</MinStay>
                            <MaxStay>3</MaxStay>
                            <Prices>
                                <Price>700.00</Price>
                                <Price weekend=\"true\">800.00</Price>
                            </Prices>
                        </Allocation>
                        <Allocation>
                            <RoomId>22905</RoomId>
                            <StartDate>2014-12-11</StartDate>
                            <EndDate>2014-12-11</EndDate>
                            <Units>2</Units>
                            <MinStay>1</MinStay>
                            <MaxStay>3</MaxStay>
                            <Prices>
                                <Price>700.00</Price>
                                <Price weekend=\"true\">800.00</Price>
                            </Prices>
                        </Allocation>
                    </Allocations>
                </ARIUpdate>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
