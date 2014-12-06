<?php
 
use MyAllocator\phpsdk\Api\LoopARIList;
use MyAllocator\phpsdk\Object\Auth;
use MyAllocator\phpsdk\Util\Common;
use MyAllocator\phpsdk\Exception\ApiAuthenticationException;
 
class LoopARIListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author nathanhelenihi
     * @group api
     */
    public function testClass()
    {
        $obj = new LoopARIList();
        $this->assertEquals('MyAllocator\phpsdk\Api\LoopARIList', get_class($obj));
    }

    public function fixtureAuthCfgObject()
    {
        $auth = Common::get_auth_env(array(
            'vendorId',
            'vendorPassword',
            'userId',
            'userPassword',
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

        $obj = new LoopARIList($fxt);

        if (!$obj->isEnabled()) {
            $this->markTestSkipped('API is disabled!');
        }

        // No optional parameters should throw exception
        $caught = false;
        try {
            $rsp = $obj->callApi();
        } catch (exception $e) {
            $caught = true;
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
        }

        if (!$caught) {
            $this->fail('should have thrown an exception');
        }

        // Arrival parameters
        $rsp = $obj->callApiWithParams(array(
            'StartDate' => '2014-12-01',
            'EndDate' => '2014-12-30'
        ));
        $this->assertTrue(isset($rsp['LoopARIs']));

/*
        // Modification parameters
        $rsp = $obj->get(array(
            'ModifcationStartDate' => '2014-12-01',
            'ModifcationEndDate' => '2014-12-05'
        ));
        print_r($rsp);
        $this->assertTrue(isset($rsp['LoopARIs']));
*/

/*
        // Creation parameters
        $rsp = $obj->callApiWithParams(array(
            'CreationStartDate' => '2014-11-01',
            'CreationEndDate' => '2014-11-30'
        ));
        $this->assertTrue(isset($rsp['LoopARIs']));
*/
    }
}
