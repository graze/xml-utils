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

use DOMDocument;
use Exception;

class XmlFormatter
{
    /**
     * Return the XML with correct indentation.
     *
     * @param string $xml
     * @return string
     * @throws Exception
     */
    public function format($xml)
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        $formatted = $dom->saveXML();

        if (!$formatted) {
            throw new Exception("An error occurred while formatting the XML");
        }

        return $formatted;
    }
}
