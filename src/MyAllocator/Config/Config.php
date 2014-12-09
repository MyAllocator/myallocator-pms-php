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

/*
 * SDK Configuration
 */

return array(
    /*
     * Enable/Disable basic request parameter validation.
     *
     * When enabled:
     *   1. Required and optional Api keys are defined as $keys
     *      array in each Api class.
     *   2. Top level required and optional keys are validated
     *      prior to sending a request to MyAllocator.
     *   3. If a required key is not present, an ApiException is thrown.
     *   4. If a top level key that is not defined in $keys is present,
     *      it is removed.
     *   5. Minimum optional parameters is enforced.
     *
     * Read dataFormat comment below for format specific validation notes.
     */ 
    'paramValidationEnabled' => true, // true, false

    /*
     * The in/out data format from your code to this SDK. This data format
     * governs the format of the request to MyAllocator and the
     * response to be returned to you. The following table
     * illustrates the formats used for the request flow based on
     * dataFormat.
     *
     *      you->SDK(dataFormat)    SDK->MA     MA->SDK     SDK->you
     *      --------------------    -------     -------     --------
     *      array                   json        json        array
     *      json                    json        json        json
     *      xml                     xml         xml         xml
     *
     * Note, parameter validation only supports array and json data formats.
     * For json data validation, the data must be decoded and re-encoded after
     * validation. If you do not wish to experience the cost, disable
     * 'paramValidationEnabled' above. For xml data, the raw request is sent
     * to MyAllocator and raw response returned to you.
     */
    'dataFormat' => 'json', // array, json, xml

    /*
     * Enable/Disable debug logs.
     */
    'debugsEnabled' => false // true, false
);
