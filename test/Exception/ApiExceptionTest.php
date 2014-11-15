<?php
 
use MyAllocator\phpsdk\Exception\ApiException;
 
class ApiExceptionTest extends PHPUnit_Framework_TestCase
{
    private $message = 'message'; 
    private $httpStatus = 'status'; 
    private $httpBody = 'body'; 
    private $jsonBody = 'body'; 

    /**
     * @author nathanhelenihi
     * @group exception
     */
    public function testApiException()
    {
        try {
            throw new ApiException(
                $this->message, 
                $this->httpStatus,
                $this->httpBody,
                $this->jsonBody
            );
        } catch (Exception $e) {
            $this->assertInstanceOf('MyAllocator\phpsdk\Exception\ApiException', $e);
            $this->assertEquals($this->message, $e->getMessage());
            $this->assertEquals($this->httpStatus, $e->getHttpStatus());
            $this->assertEquals($this->httpBody, $e->getHttpBody());
            $this->assertEquals($this->jsonBody, $e->getJsonBody());
        }
    }
}
