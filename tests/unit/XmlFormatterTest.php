<?php

namespace Graze\XmlUtils\Tests\Unit;

use Graze\XmlUtils\XmlFormatter;
use PHPUnit_Framework_TestCase;

class XmlFormatterTest extends PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $unformattedXml = '<?xml version="1.0" encoding="UTF-8"?><root><child1>123</child1><child2 attribute="1"><subchild1>456</subchild1></child2></root>';
        
        $expectedFormattedXml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <child1>123</child1>
  <child2 attribute="1">
    <subchild1>456</subchild1>
  </child2>
</root>

EOF;

        $xmlFormatter = new XmlFormatter();
        $formattedXml = $xmlFormatter->format($unformattedXml);

        $this->assertEquals($expectedFormattedXml, $formattedXml);
    }
}
