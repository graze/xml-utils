<?php

namespace Graze\XmlUtils\Tests\Unit;

use Graze\XmlUtils\XmlConverter;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class XmlConverterTest extends TestCase
{
    /**
     * @return mixed[]
     */
    public function addArrayAsChildrenDataProvider()
    {
        $data = [];

        // Basic test
        $array = [
            'child' => 123
        ];
        $expectedChildXml = '<child>123</child>';
        $data[] = [$array, $expectedChildXml];

        // Attributes
        $array = [
            'child' => [
                '@attributes' => [
                    'id' => '1',
                    'name' => 'foo'
                ]
            ]
        ];
        $expectedChildXml = '<child id="1" name="foo"/>';
        $data[] = [$array, $expectedChildXml];

        // Attibute with value
        $array = [
            'child' => [
                '@attributes' => [
                    'id' => '1'
                ],
                '@value' => 123
            ]
        ];
        $expectedChildXml = '<child id="1">123</child>';
        $data[] = [$array, $expectedChildXml];

        // Multiple children
        $array = [
            'child1' => 111,
            'child2' => 222
        ];
        $expectedChildXml = '<child1>111</child1><child2>222</child2>';
        $data[] = [$array, $expectedChildXml];

        // Multiple children with the name node name
        $array = [
            'child' => [
                [
                    'item' => 111
                ],
                [
                    'item' => 222
                ]
            ]
        ];
        $expectedChildXml = '<child><item>111</item></child><child><item>222</item></child>';
        $data[] = [$array, $expectedChildXml];

        // Ignore empty value
        $array = [
            'child1' => 111,
            'child2' => ''
        ];
        $expectedChildXml = '<child1>111</child1>';
        $data[] = [$array, $expectedChildXml];

        // Complex example
        $array = [
            'child1' => 111,
            'child2' => [
                'item' => [
                    [
                        '@attributes' => [
                            'id' => 123
                        ]
                    ],
                    [
                        'property' => 456
                    ]
                ]
            ]
        ];
        $expectedChildXml = '<child1>111</child1><child2><item id="123"/><item><property>456</property></item></child2>';
        $data[] = [$array, $expectedChildXml];

        return $data;
    }

    /**
     * @dataProvider addArrayAsChildrenDataProvider
     * @param array $array
     * @param string $expectedChildXml
     */
    public function testAddArrayAsChildren(array $array, $expectedChildXml)
    {
        $templateXml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root>%s</root>\n";

        $expectedXml = sprintf($templateXml, $expectedChildXml);

        $xmlElement = new SimpleXMLElement(sprintf($templateXml, ''));

        $xmlConverter = new XmlConverter();
        $xmlConverter->addArrayAsChildren($array, $xmlElement);

        $this->assertEquals($expectedXml, $xmlElement->asXml());
    }

    public function testConvertToArray()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><root><child1>123</child1><child2 id="1"><subchild1>456</subchild1></child2></root>';

        $xmlElement = new SimpleXMLElement($xml);

        $expectedArray = [
            'child1' => '123',
            'child2' => [
                '@attributes' => [
                    'id' => '1'
                ],
                'subchild1' => '456'
            ]
        ];

        $xmlConverter = new XmlConverter();
        $array = $xmlConverter->convertToArray($xmlElement);

        $this->assertEquals($expectedArray, $array);
    }
}
