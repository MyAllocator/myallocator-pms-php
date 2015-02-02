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

namespace MyAllocator\phpsdk\tests\json;
use MyAllocator\phpsdk\src\Api\ARIRulesUpdate;
use MyAllocator\phpsdk\src\Object\Auth;
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

        $obj = new ARIRulesUpdate($fxt);
        $obj->setConfig('dataFormat', 'array');

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

/*
        $data = array(
            'ARIRuleDelete' => array(
                array(
                    'PMSRuleId' => '00001-boo',
                    'RoomId' => '23651',
                    'Channel' => 'boo',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-03-01',
                    'EndDate' => '2015-03-30'
                ),
                array(
                    'PMSRuleId' => '00001-exp',
                    'RoomId' => '23651',
                    'Channel' => 'exp',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-03-01',
                    'EndDate' => '2015-03-30'
                ),
                array(
                    'PMSRuleId' => '00002-boo',
                    'RoomId' => '23651',
                    'Channel' => 'boo',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-05-01',
                    'EndDate' => '2015-05-30'
                ),
                array(
                    'PMSRuleId' => '00003-exp',
                    'RoomId' => '23651',
                    'Channel' => 'exp',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-04-01',
                    'EndDate' => '2015-06-30'
                ),
                array(
                    'PMSRuleId' => '00004-loop',
                    'RoomId' => '23651',
                    'Channel' => 'loop',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-04-01',
                    'EndDate' => '2015-04-30'
                )
            )
        );
*/

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
                ),/*
                array(
                    '_Action' => 'Append',
                    'PMSRuleId' => '00002-loop',
                    'RoomId' => '23651',
                    'Channel' => 'loop',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-03-20',
                    'EndDate' => '2015-04-10'
                ),*/
                /*
                /*array(
                    '_Action' => 'Append',
                    'PMSRuleId' => '00001-loop',
                    'RoomId' => '23651',
                    'Channel' => 'loop',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-02-20',
                    'EndDate' => '2015-03-10'
                ),*//*
                array(
                    '_Action' => 'Append',
                    'PMSRuleId' => '00006-boo',
                    'RoomId' => '23651',
                    'Channel' => 'boo',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-04-01',
                    'EndDate' => '2015-04-30'
                ),*/
                /*array(
                    '_Action' => 'Update',
                    'PMSRuleId' => '00001-boo',
                    'RoomId' => '23651',
                    'Channel' => 'boo',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-03-05',
                    'EndDate' => '2015-03-10'
                ),
                array(
                    '_Action' => 'Update',
                    'PMSRuleId' => '00006-boo',
                    'RoomId' => '23651',
                    'Channel' => 'boo',
                    'Verb' => 'BLOCK',
                    'StartDate' => '2015-04-05',
                    'EndDate' => '2015-04-10'
                ),*/
                /*array(
                    '_Action' => 'Delete',
                    'PMSRuleId' => '00006-boo'
                ),*/
                /*array(
                    '_Action' => 'Delete',
                    'PMSRuleId' => '00001-exp'
                ),
                array(
                    '_Action' => 'Delete',
                    'PMSRuleId' => '00002-boo'
                ),
                array(
                    '_Action' => 'Delete',
                    'PMSRuleId' => '00003-exp'
                ),
                array(
                    '_Action' => 'Delete',
                    'PMSRuleId' => '00004-loop'
                )*/
            )
        );

        $rsp = $obj->callApiWithParams($data);
        $this->assertTrue(isset($rsp['response']['body']['Success']));
    }
}
