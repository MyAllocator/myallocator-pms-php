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
 
use MyAllocator\phpsdk\src\Util\Requestor;

class RequestorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group util
     */
    public function testClass()
    {
        $obj = new Requestor();
        $this->assertEquals('MyAllocator\phpsdk\src\Util\Requestor', get_class($obj));
    }

    /**
     * @author nathanhelenihi
     * @group util
     */
    public function testEncode()
    {
        $a = array(
            'my' => 'value',
            'that' => array('your' => 'example'),
            'bar' => 1,
            'baz' => null
        );

        $enc = Requestor::encode($a);
        $this->assertEquals($enc, 'my=value&that%5Byour%5D=example&bar=1');

        $a = array('that' => array('your' => 'example', 'foo' => null));
        $enc = Requestor::encode($a);
        $this->assertEquals($enc, 'that%5Byour%5D=example');

        $a = array('that' => 'example', 'foo' => array('bar', 'baz'));
        $enc = Requestor::encode($a);
        $this->assertEquals($enc, 'that=example&foo%5B%5D=bar&foo%5B%5D=baz');

        $a = array(
            'my' => 'value',
            'that' => array('your' => array('cheese', 'whiz', null)),
            'bar' => 1,
            'baz' => null
        );

        $enc = Requestor::encode($a);
        $expected = 'my=value&that%5Byour%5D%5B%5D=cheese'
            . '&that%5Byour%5D%5B%5D=whiz&bar=1';
        $this->assertEquals($enc, $expected);
    }

    /**
     * @author nathanhelenihi
     * @group util
     */
    public function testUtf8()
    {
        // UTF-8 string
        $x = "\xc3\xa9";
        $this->assertEquals(Requestor::utf8($x), $x);

        // Latin-1 string
        $x = "\xe9";
        $this->assertEquals(Requestor::utf8($x), "\xc3\xa9");

        // Not a string
        $x = TRUE;
        $this->assertEquals(Requestor::utf8($x), $x);
    }
}
