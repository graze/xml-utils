<?php
/**
 * This file is part of graze/telnet-client.
 *
 * Copyright (c) 2018 Nature Delivered Ltd. <https://www.graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://github.com/graze/xml-utils/blob/master/LICENSE
 * @link https://github.com/graze/xml-utils
 */

namespace Graze\XmlUtils;

use Exception;
use SimpleXMLElement;

class XmlConverter
{
    /**
     * Convert an array into XML and adds the whole structure as a child of the existing SimpleXMLElement given.
     * An array of inserted children is returned so you can add further children to them if necessary.
     *
     * @param array $array
     * @param SimpleXMLElement $xmlElement
     * @param bool $ignoreEmptyElements
     * @return SimpleXMLElement[]|array
     */
    public function addArrayAsChildren(array $array, SimpleXMLElement $xmlElement, $ignoreEmptyElements = true)
    {
        // Keep an array of children that are added to $xmlElement
        $children = [];

        // Add each element of the array as a child to $xmlElement
        foreach ($array as $key => $value) {
            // Ignore empty array elements
            if ($this->isEmpty($value) && $ignoreEmptyElements) {
                continue;
            }

            // Specifies attributes as a key/value array
            if ($key === '@attributes') {
                if (!is_array($value)) {
                    throw new Exception('@attributes must be an array');
                }

                foreach ($value as $attributeName => $attributeValue) {
                    $xmlElement->addAttribute($attributeName, htmlspecialchars($attributeValue));
                }

                continue;
            }

            // Special case to be able to set the value of an element to a string if the attribute is also being set,
            // without this a child element would be created instead.
            // The really hacky part is that setting the 0 key element overrides the value (it doesn't normally exist).
            if ($key === '@value') {
                if (!is_scalar($value)) {
                    throw new Exception('@value must be a scalar');
                }

                $xmlElement[0] = htmlspecialchars($value);
                continue;
            }

            // The value contains sub elements.
            // If the array is numeric then there are multiple of the same element.
            if (is_array($value)) {
                $useSubArray = key($value) === 0;
                $subValues = $useSubArray ? $value : [$value];

                $subChildren = [];
                foreach ($subValues as $subValue) {
                    // Recursively add child elements.
                    if (is_array($subValue)) {
                        $subChild = $xmlElement->addChild($key);
                        $this->addArrayAsChildren($subValue, $subChild, $ignoreEmptyElements);
                    } else {
                        $subChild = $xmlElement->addChild($key, $subValue);
                    }
                    $subChildren[] = $subChild;
                }

                $children[$key] = $useSubArray ? $subChildren : $subChildren[0];
                continue;
            }

            // Encode the value correctly
            $value = htmlspecialchars($value);
            $children[$key] = $xmlElement->addChild($key, $value);
        }

        return $children;
    }

    /**
     * Return whether the value, either an array (flat or multidimensional) or a string is empty. A string is empty if
     * its length is zero. An array is empty if all of its elements are empty.
     *
     * @param array|string $value
     * @return bool
     */
    private function isEmpty($value)
    {
        // Assume that the value is empty
        $empty = true;

        // If the value is an array
        if (is_array($value)) {
            foreach ($value as $element) {
                // The value is empty if all elements of the array are empty
                $empty = $empty && $this->isEmpty($element);
            }
        } else {
            // We can't use the empty function here because it treats "0" as empty
            $empty = strlen($value) == 0;
        }

        return $empty;
    }

    /**
     * Convert a SimpleXMLElement to an array.
     *
     * @param SimpleXMLElement $xmlElement
     * @return array
     */
    public function convertToArray(SimpleXMLElement $xmlElement)
    {
        return json_decode(json_encode($xmlElement), true);
    }
}
