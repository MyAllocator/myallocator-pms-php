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

namespace MyAllocator\phpsdk\src\Exception;

/**
 * The MyAllocator base exception class.
 */
class MaException extends \Exception
{
    /**
     * @var array State data before the exception.
     */
    protected $state = null;

    /**
     * The constructor may set request/response parameters.
     *
     * @param string $msg The exception description.
     * @param array $args The request/response parameters.
     */
    public function __construct($msg, $state = null)
    {
        parent::__construct($msg);

        if (isset($state)) {
            $this->state = $state;
        }
    }

    /**
     * Get the state of an exception
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Get the http status code of an exception.
     */
    public function getHttpStatus()
    {
        if ($this->state && isset($this->state['response']['code'])) {
            return $this->state['response']['code'];
        } else {
            return null;
        }
    }

    /**
     * Get the http body of an exception.
     */
    public function getHttpBody()
    {
        if ($this->state && isset($this->state['response']['body'])) {
            return $this->state['response']['body'];
        } else {
            return null;
        }
    }

    /**
     * Get the http body as json of an exception.
     */
    public function getJsonBody()
    {
        if ($this->state && isset($this->state['response']['body_raw'])) {
            return $this->state['response']['body_raw'];
        } else {
            return null;
        }
    }
}
