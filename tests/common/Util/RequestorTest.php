<?php
 
use MyAllocator\phpsdk\src\Util\Requestor;
use MyAllocator\phpsdk\src\Object\Auth as Auth;
 
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
