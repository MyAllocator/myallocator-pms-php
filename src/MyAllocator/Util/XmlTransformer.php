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

namespace MyAllocator\phpsdk\src\Util;

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

        $xml = str_replace('Auth/', 'Auth', $xml);
        $xml = str_replace('Callback/', 'Callback', $xml);

        $obj = simplexml_load_string($xml);
        if (!$obj) {
            return false;
        }

        return json_decode(json_encode($obj), TRUE);
    }

    function xmlToArray2($xml, $options = array())
    {
        $defaults = array(
            'namespaceSeparator' => ':',//you may want this to be something other than a colon
            'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
            'alwaysArray' => array(),   //array of xml tag names which should always become arrays
            'autoArray' => true,        //only create arrays for tags which appear more than once
            'textContent' => '$',       //key used for the text content of elements
            'autoText' => true,         //skip textContent key if node has no attributes or child nodes
            'keySearch' => false,       //optional search and replace on tag and attribute names
            'keyReplace' => false       //replace values for above search values (as passed to str_replace())
        );
        $options = array_merge($defaults, $options);
        $namespaces = $xml->getDocNamespaces();
        $namespaces[''] = null; //add base (empty) namespace
     
        //get attributes from all namespaces
        $attributesArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
                //replace characters in attribute name
                if ($options['keySearch']) $attributeName =
                        str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
                $attributeKey = $options['attributePrefix']
                        . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                        . $attributeName;
                $attributesArray[$attributeKey] = (string)$attribute;
            }
        }
     
        //get child nodes from all namespaces
        $tagsArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->children($namespace) as $childXml) {
                //recurse into child nodes
                $childArray = xmlToArray2($childXml, $options);
                list($childTagName, $childProperties) = each($childArray);
     
                //replace characters in tag name
                if ($options['keySearch']) $childTagName =
                        str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
                //add namespace prefix, if any
                if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
     
                if (!isset($tagsArray[$childTagName])) {
                    //only entry with this key
                    //test if tags of this type should always be arrays, no matter the element count
                    $tagsArray[$childTagName] =
                            in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                            ? array($childProperties) : $childProperties;
                } elseif (
                    is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                    === range(0, count($tagsArray[$childTagName]) - 1)
                ) {
                    //key already exists and is integer indexed array
                    $tagsArray[$childTagName][] = $childProperties;
                } else {
                    //key exists so convert to integer indexed array with previous value in position 0
                    $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
                }
            }
        }
     
        //get text content of node
        $textContentArray = array();
        $plainText = trim((string)$xml);
        if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
     
        //stick it all together
        $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
                ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
     
        //return node as array
        return array(
            $xml->getName() => $propertiesArray
        );
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
                $elementChild = $this->arrayToXmlNode($value, $key, $element, $defaultElementName);
                $parentNode->appendChild($elementChild);
            }
        }
        return $parentNode;
    }
}
