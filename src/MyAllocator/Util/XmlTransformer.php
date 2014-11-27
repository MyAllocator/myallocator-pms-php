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

namespace MyAllocator\phpsdk\Util;

class XmlTransformer {

    /**
     * Converts xml to array.
     *
     * @param string $xml
     * @return array
     */
    public function xmlToArray($xml = null)
    {
        if (!$xml) {
            return false;
        }

        $obj = simplexml_load_string($xml);
        if (!$obj) {
            return false;
        }

        return json_decode(json_encode($obj), TRUE);
    }

    /**
     * Converts array to xml (Dom Document).
     *
     * @param array $array
     * @param string $rootElementName
     * @param string $defaultElementName
     * @return Ambigous <DOMDocument>
     */
    public function arrayToXml(
        array $array,
        $rootElementName = 'data',
        $defaultElementName = 'item'
    ) {

        // Convert keys containing '/' into arrays
        // TODO, this is only temporary until API takes 'Auth' => array(...)
        // Also, this only formats top level keys

        foreach ($array as $k => $v) {
            if (strpos($k, '/') !== FALSE) {
                list($outer_key, $inner_key) = explode("/", $k);
                $array[$outer_key][$inner_key] = $v;
                unset($array[$k]);
            } 
        }

        return $this->arrayToXmlNode($array, 
            $rootElementName, 
            null, 
            $defaultElementName
        );
    }

    /**
     * Converts array data to Xml nodes.
     * 
     * @param Ambigous <string, mixed> $elementName Used as element tagname. If it's not a string $defaultElementName is used instead.
     * @param Ambigous <string, array> $elementContent
     * @param Ambigous <\DOMDocument, NULL, \DOMElement> $parentNode The parent node is
     *  either a \DOMDocument (by the method calls from outside of the method)
     *  or a \DOMElement or NULL (by the calls from inside).
     *  Once again: For the calls from outside of the method the argument MUST be either a \DOMDocument object or NULL.
     * @param string $defaultElementName If the key of the array element is a string, it determines the DOM element name / tagname.
     *  For numeric indexes the $defaultElementName is used.
     * @return \DOMDocument
     */
    protected function arrayToXmlNode(
        $elementContent,
        $elementName,
        \DOMNode $parentNode = null,
        $defaultElementName = 'item'
    ) {
        $parentNode = is_null($parentNode) ? new \DOMDocument('1.0', 'utf-8') : $parentNode;
        $name = is_string($elementName) ? $elementName : $defaultElementName;
        if (!is_array($elementContent)) {
            $content = htmlspecialchars($elementContent);
            $element = new \DOMElement($name, $content);
            $parentNode->appendChild($element);
        } else {
            $element = new \DOMElement($name);
            $parentNode->appendChild($element);
            foreach ($elementContent as $key => $value) {
                $elementChild = $this->arrayToXmlNode($value, $key, $element);
                $parentNode->appendChild($elementChild);
            }
        }
        return $parentNode;
    }
}
