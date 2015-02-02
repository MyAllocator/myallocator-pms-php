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

namespace MyAllocator\phpsdk\src;

/**
 * The MyAllocator base class to be extended by API's and Utilities.
 */
class MaBaseClass
{
    /**
     * @var array MyAllocator API configuration.
     */
    protected $config = null;

    /**
     * Class contructor attempts to assign configuration parameters.
     *
     * @param mixed $cfg API configuration potentially containing a
     *        'cfg' key with configurations to overwrite Config/Config.php.
     */
    public function __construct($cfg = null)
    {
        // Load configuration from parameters or file
        if (isset($cfg) && isset($cfg['cfg'])) {
            $this->config = $this->sanitizeCfg($cfg['cfg']);
        } else {
            // Throws exception if cannot find config file
            $cfg = require(dirname(__FILE__) . '/Config/Config.php');
            $this->config = $this->sanitizeCfg($cfg);
        }
    }

    /**
     * Set an API configuration key.
     *
     * @param key $key The configuration key. 
     * @param value $value The configuration key value. 
     *
     * @return boolean|null Result of the set.
     */
    public function setConfig($key = null, $value = null)
    {
        if ($key == null || $value == null) {
            return null;
        }
        return ($this->config[$key] = $value);
    }

    /**
     * Get an API configuration value by key.
     *
     * @param key $key The configuration key. 
     *
     * @return mixed|null The configuration value.
     */
    public function getConfig($key)
    {
        return (isset($this->config[$key])) ? $this->config[$key] : null;
    }

    /**
     * Sanitize parameter config data. Ensure keys/values are valid data.
     * Unknown keys are removed.
     *
     * @param array $cfg API configurations. 
     *
     * @return array Configuration containing a valid configuration set. It
     *  may or may not include the supplied configuration parameters,
     *  depending on their validity.
     */
    private function sanitizeCfg($cfg)
    {
        $sanitize = array(
            'paramValidationEnabled' => array(
                'type' => 'boolean',
                'default' => true,
                'valid' => array(true, false)
            ),
            'dataFormat' => array(
                'type' => 'string',
                'default' => 'array',
                'valid' => array('array', 'json', 'xml')
            ),
            'dataResponse' => array(
                'type' => 'array',
                'default' => array('timeRequest', 'timeResponse', 'request'),
                'valid' => array('timeRequest', 'timeResponse', 'request')
            ),
            'httpErrorThrowsException' => array(
                'type' => 'boolean',
                'default' => false,
                'valid' => array(true, false)
            ),
            'debugsEnabled' => array(
                'type' => 'boolean',
                'default' => false,
                'valid' => array(true, false)
            )
        );

        $result = array();
        foreach ($sanitize as $k => $v) {
            if ($v['type'] == 'array') {
                if (!isset($cfg[$k]) || !is_array($cfg[$k])) {
                    // Set to default if not set or invalid value
                    $result[$k] = $v['default'];
                } else {
                    // Validate array keys
                    foreach ($cfg[$k] as $index => $item) {
                        if (!in_array($item, $v['valid'])) {
                            unset($cfg[$k][$index]);
                        }
                    }
                    // Set to parameter if value is set and valid
                    $result[$k] = $cfg[$k];
                }
            } else {
                if (!isset($cfg[$k]) || !in_array($cfg[$k], $v['valid'], true)) {
                    // Set to default if not set or invalid value
                    $result[$k] = $v['default'];
                } else {
                    // Set to parameter if value is set and valid
                    $result[$k] = $cfg[$k];
                }
            }
        }

        return $result;
    }

    /**
     * Echoes a string if debugsEnabled is set to true.
     *
     * @param string $str The string to echo.
     *
     */
    protected function debug_echo($str)
    {
        $this->debug('echo', $str);
    }

    /**
     * Dumps an object/variable if debugsEnabled is set to true.
     *
     * @param mixed $obj The object or vairable to dump.
     */
    protected function debug_var_dump($obj)
    {
        $this->debug('var_dump', $obj);
    }

    /**
     * Prints an array if debugsEnabled is set to true.
     *
     * @param array $array The array to print.
     */
    protected function debug_print_r($array)
    {
        $this->debug('print_r', $array);
    }

    /**
     * Generates some output if debugsEnabled is set to true.
     *
     * @param string $type The output type.
     * @param mixed $mixed The object, array, or variable.
     */
    protected function debug($type, $mixed)
    {
        if ($this->config && $this->config['debugsEnabled']) {
            switch ($type) {
                case 'echo':
                    echo $mixed;
                    break;
                case 'print_r':
                    print_r($mixed);
                    break;
                case 'var_dump':
                    var_dump($mixed);
                    break;
            }
        }
    }
}
