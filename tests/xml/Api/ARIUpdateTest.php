<?php
/**
 * Copyright (C) 2020 Digital Arbitrage, Inc
 *
 * A copy of the LICENSE can be found in the LICENSE file within
 * the root directory of this library.  
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */

namespace MyAllocator\phpsdk\tests\xml;
 
use MyAllocator\phpsdk\src\Api\ARIUpdate;
use MyAllocator\phpsdk\src\Util\Common;

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

        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <ARIUpdate>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                        <UserToken>{$auth->userToken}</UserToken>
                        <PropertyId>{$auth->propertyId}</PropertyId>
                    </Auth>
                    <Options>
                        <QueryForStatus>true</QueryForStatus>
                        <loop_delay>30</loop_delay>
                        <FailIfUpdateActive>false</FailIfUpdateActive>
                    </Options>
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
        $this->assertEquals(200, $rsp['response']['code']);
        $this->assertFalse(
            strpos($rsp['response']['body'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
