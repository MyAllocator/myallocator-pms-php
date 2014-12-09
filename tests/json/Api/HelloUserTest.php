<?php

namespace MyAllocator\phpsdk\tests\json;
 
use MyAllocator\phpsdk\src\Api\HelloUser;
use MyAllocator\phpsdk\src\Object\Auth;
use MyAllocator\phpsdk\src\Util\Common;
use MyAllocator\phpsdk\src\Exception\ApiAuthenticationException;
 
class HelloUserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new HelloUser();
        $this->assertEquals('MyAllocator\phpsdk\src\Api\HelloUser', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::getAuthEnv(array(
            'vendorId', 
            'vendorPassword',
            'userId', 
            'userPassword'
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

        // Auth null
        $obj = new HelloUser();
        $obj->setConfig('dataFormat', 'array');
        try {
            $rsp = $obj->callApiWithParams(array(
                'hello' => 'world'
            ));
        } catch (Exception $e) {
            $this->assertInstanceOf('MyAllocator\phpsdk\src\Exception\ApiAuthenticationException', $e);
        }

        // Successful call
        $obj = new HelloUser($fxt);
        $obj->setConfig('dataFormat', 'array');
        $rsp = $obj->callApiWithParams(array(
            'hello' => 'world'
        ));
        $this->assertTrue(isset($rsp['response']['hello']));
        $this->assertEquals('world', $rsp['response']['hello']);
    }
}
