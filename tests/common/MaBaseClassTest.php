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
 
use MyAllocator\phpsdk\src\MaBaseClass;
 
class MaBaseClassTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     */
    public function testClass()
    {
        $obj = new MaBaseClass();
        $this->assertEquals('MyAllocator\phpsdk\src\MaBaseClass', get_class($obj));
    }

    /**
     * @author nathanhelenihi
     */
    public function testConstructorConfigDefault()
    {
        $obj = new MaBaseClass();
        $this->assertNotNull($obj->getConfig('paramValidationEnabled'));
        $this->assertNotNull($obj->getConfig('dataFormat'));
        $this->assertNotNull($obj->getConfig('debugsEnabled'));
    }

    public function fixtureConstructorConfigParameters()
    {
        $data = array();

        $data[] = array(array(
            'shouldSucceed' => true,
            'cfg' => array(
                'paramValidationEnabled' => true
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => true,
            'cfg' => array(
                'paramValidationEnabled' => false
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'paramValidationEnabled' => null
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'paramValidationEnabled' => ''
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'paramValidationEnabled' => 'xxx'
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => true,
            'cfg' => array(
                'debugsEnabled' => true
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => true,
            'cfg' => array(
                'debugsEnabled' => false
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'debugsEnabled' => null
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'debugsEnabled' => 'xxx'
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'debugsEnabled' => ''
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => true,
            'cfg' => array(
                'dataFormat' => 'array'
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => true,
            'cfg' => array(
                'dataFormat' => 'json'
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => true,
            'cfg' => array(
                'dataFormat' => 'xml'
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'dataFormat' => null
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'dataFormat' => 'xxx'
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => false,
            'cfg' => array(
                'dataFormat' => ''
            )
        ));

        $data[] = array(array(
            'shouldSucceed' => true,
            'cfg' => array(
                'paramValidationEnabled' => true,
                'dataFormat' => 'array',
                'debugsEnabled' => true
            )
        ));

        return $data;
    }

    /**
     * @author nathanhelenihi
     * @dataProvider fixtureConstructorConfigParameters
     */
    public function testConstructorConfigParameters(array $fxt)
    {
        $obj = new MaBaseClass($fxt);

        if (isset($fxt['cfg']['paramValidationEnabled'])) {
            if ($fxt['shouldSucceed']) {
                $this->assertSame(
                    $fxt['cfg']['paramValidationEnabled'],
                    $obj->getConfig('paramValidationEnabled')
                );
            } else {
                $this->assertNotSame(
                    $fxt['cfg']['paramValidationEnabled'],
                    $obj->getConfig('paramValidationEnabled')
                );
            }
        }

        if (isset($fxt['cfg']['debugsEnabled'])) {
            if ($fxt['shouldSucceed']) {
                $this->assertSame(
                    $fxt['cfg']['debugsEnabled'],
                    $obj->getConfig('debugsEnabled')
                );
            } else {
                $this->assertNotSame(
                    $fxt['cfg']['debugsEnabled'],
                    $obj->getConfig('debugsEnabled')
                );
            }
        }

        if (isset($fxt['cfg']['dataFormat'])) {
            if ($fxt['shouldSucceed']) {
                $this->assertSame(
                    $fxt['cfg']['dataFormat'],
                    $obj->getConfig('dataFormat')
                );
            } else {
                $this->assertNotSame(
                    $fxt['cfg']['dataFormat'],
                    $obj->getConfig('debugsFormat')
                );
            }
        }
    }

    /**
     * @author nathanhelenihi
     */
    public function testSetConfig()
    {
        $obj = new MaBaseClass();

        // Null key & value
        $this->assertNull($obj->setConfig(null, null));
        $this->assertNull($obj->setConfig('', ''));

        // Null key
        $this->assertNull($obj->setConfig(null, 'value'));
        $this->assertNull($obj->setConfig('', 'value'));

        // Null value
        $this->assertNull($obj->setConfig('key', null));
        $this->assertNull($obj->setConfig('key', ''));

        // Should Succeed
        $this->assertSame('someval', $obj->setConfig('somekey', 'someval'));
        $this->assertSame('someval', $obj->getConfig('somekey'));
    }

    /**
     * @author nathanhelenihi
     */
    public function testGetConfig()
    {
        $cfg = array(
            'paramValidationEnabled' => true,
            'dataFormat' => 'array',
            'debugsEnabled' => true
        );
        $obj = new MaBaseClass(array(
            'cfg' => $cfg
        ));

        // Bad parameters
        $this->assertNull($obj->getConfig(null));
        $this->assertNull($obj->getConfig(false));
        $this->assertNull($obj->getConfig(''));

        // Should succeed
        $this->assertSame(
            $cfg['paramValidationEnabled'],
            $obj->getConfig('paramValidationEnabled')
        );
        $this->assertSame(
            $cfg['dataFormat'],
            $obj->getConfig('dataFormat')
        );
        $this->assertSame(
            $cfg['debugsEnabled'],
            $obj->getConfig('debugsEnabled')
        );
    }
}
