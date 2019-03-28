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

namespace MyAllocator\phpsdk\tests\json;

use MyAllocator\phpsdk\src\Api\ARIRulesUpdate;
use MyAllocator\phpsdk\src\Util\Common;
 
class ARIRulesUpdateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new ARIRulesUpdate();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\ARIRulesUpdate', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'propertyId',
            'userToken',
            'vendorId',
            'vendorPassword',
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

        $obj = new ARIRulesUpdate($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        $data = array(
            'ARIRules' => array(
                array(
                    '_Action' => 'Upsert',
                    'PMSRuleId' => '00009-boo',
                    'RoomId' => '23651',
                    'Channel' => 'boo',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-05-18',
                    'EndDate' => '2015-05-20'
                ),
            )
        );

        $rsp = $obj->callApiWithParams($data);
        $this->assertTrue(isset($rsp['response']['body']['Success']));
    }
}
