<?php
/**
 * Copyright (C) 2019 MyAllocator
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
 
use MyAllocator\phpsdk\src\Api\PropertyImageCreate;
use MyAllocator\phpsdk\src\Util\Common;

class PropertyImageCreateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new PropertyImageCreate();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\PropertyImageCreate', get_class($obj));
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

        $obj = new PropertyImageCreate($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // Invalid booking id should fail
        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <PropertyImageCreate>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                        <UserToken>{$auth->userToken}</UserToken>
                        <PropertyId>{$auth->propertyId}</PropertyId>
                    </Auth>
                    <CreatePropertyImage>
                        <PropertyImages>
                            <PropertyImage>
                                <Filename>some-file.jpg</Filename>
                                <Data>
                                    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
                                </Data>
                            </PropertyImage>
                        </PropertyImages>
                    </CreatePropertyImage>
                </PropertyImageCreate>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['response']['code']);
        $this->assertFalse(
            strpos($rsp['response']['body'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
