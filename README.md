# xml-utils

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graze/xml-utils.svg?style=flat-square)](https://packagist.org/packages/graze/xml-utils)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/graze/xml-utils/master.svg?style=flat-square)](https://travis-ci.org/graze/xml-utils)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/graze/xml-utils.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/xml-utils/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/graze/xml-utils.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/xml-utils)
[![Total Downloads](https://img.shields.io/packagist/dt/graze/xml-utils.svg?style=flat-square)](https://packagist.org/packages/graze/xml-utils)

XML utilities, mainly conversion between XML <=> array.  

## Install

Via Composer

``` bash
$ composer require graze/xml-utils
```

## Usage

### Converting an array to XML 

```php
$array = [
    'child1' => 111,
    'child2' => 222
];

$xmlElement = new SimpleXMLElement('<root/>');

$xmlConverter = new Graze\XmlUtils\XmlConverter();
$xmlConverter->addArrayAsChildren($array, $xmlElement);

echo $xmlElement->asXml();
```

result:

```
?xml version="1.0"?>
<root><child1>111</child1><child2>222</child2></root>
```

#### Attributes

Attributes are supported by using `@attributes` key with an array of attribute name => value.

```
$array = [
    'child1' => [
        '@attributes' => [
            'id' => 123
        ]
    ]
];
```

result:

```
<child1 id="123"/>
```

#### Attributes with value

If a value needs to be set for an element with an attribute then the `@value` key should be used.

```
$array = [
    'child1' => [
        '@attributes' => [
            'id' => 123
        ],
        '@value' => 'some description'
    ]
];
```

result:

```
<child1 id="123">some description</child1>
```

#### Repeated elements

Repeated elements are supported with indexed arrays.  

```
$array = [
    'child' => [
        'first',
        'second'
    ]
];
```

result:

```
<child>first</child><child>second</child>
```

### Converting XML to an array 

```
$xml = '<root><child1>123</child1><child2 id="1"><subchild1>456</subchild1></child2></root>';

$xmlElement = new SimpleXMLElement($xml);

$xmlConverter = new XmlConverter();
$array = $xmlConverter->convertToArray($xmlElement);
```

result:

```
[
    'child1' => '123',
    'child2' => [
        '@attributes' => [
            'id' => '1'
        ],
        'subchild1' => '456'
    ]
];
```

### Formatting XML

Formats XML so it is easier to read.  

```
$unformattedXml = '<?xml version="1.0" encoding="UTF-8"?><root><child1>123</child1><child2 attribute="1"><subchild1>456</subchild1></child2></root>';

$xmlFormatter = new XmlFormatter();
$formattedXml = $xmlFormatter->format($unformattedXml);
```
        
result:

```
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <child1>123</child1>
  <child2 attribute="1">
    <subchild1>456</subchild1>
  </child2>
</root>
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```shell
make build test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email security@graze.com instead of using the issue tracker.

## Credits

- [Brendan Kay](https://github.com/brendankay)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
