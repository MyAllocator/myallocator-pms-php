<?php
/**
 * Copyright (C) 2014 MyAllocator
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
 
use MyAllocator\phpsdk\src\Api\PropertyChannelList;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
 
class PropertyChannelListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new PropertyChannelList();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\PropertyChannelList', get_class($obj));
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

        $obj = new PropertyChannelList($fxt);
        $obj->setConfig('dataFormat', 'xml');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $auth = $fxt['auth'];
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <PropertyChannelList>
                    <Auth>
                        <VendorId>{$auth->vendorId}</VendorId>
                        <VendorPassword>{$auth->vendorPassword}</VendorPassword>
                        <UserToken>{$auth->userToken}</UserToken>
                        <PropertyId>{$auth->propertyId}</PropertyId>
                    </Auth>
                </PropertyChannelList>
        ";

        $rsp = $obj->callApiWithParams($xml);
        $this->assertEquals(200, $rsp['code']);
        $this->assertFalse(
            strpos($rsp['response'], '<Errors>'),
            'Response contains errors!'
        );
    }
}
