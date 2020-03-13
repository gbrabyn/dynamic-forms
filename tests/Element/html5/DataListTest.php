<?php

use GBrabyn\DynamicForms\Element\Attributes;
use GBrabyn\DynamicForms\Element\html5\DataList;
use GBrabyn\DynamicForms\Element\Options;
use GBrabyn\DynamicForms\Element\LaminasEscaperWrapper;
use Laminas\Escaper\Escaper;

class DataListTest extends PHPUnit_Framework_TestCase
{
    public function testGetWithoutErrorMessage()
    {
        $options = new Options();
        $options->add('3', 'March');
        $options->add('4', 'April');
        $options->add('5', 'May');

        $escaper = new LaminasEscaperWrapper( new Escaper('utf-8') );
        $obj = new DataList($escaper, $options);

        $attributes = new Attributes(['required', 'id'=>'b123']);
        $obj->setAttributes($attributes);

        $element = (string)$obj;
        $expected = <<<EOT
<datalist id="b123" required><option value="3">March</option><option value="4">April</option><option value="5">May</option></datalist>
EOT;
        $this->assertEquals(trim($expected), $element);
    }

    public function testIdRequired()
    {
        $options = new Options();

        $escaper = new LaminasEscaperWrapper( new Escaper('utf-8') );
        $obj = new DataList($escaper, $options);
        $obj->setAttributes(new Attributes([]));

        $this->expectException(\InvalidArgumentException::class);

        $element = $obj->__toString();
    }

    public function testGetOptionValues()
    {
        $options = new Options();
        $options->add('3', 'March');
        $options->add('4', 'April');
        $options->add('5', 'May');

        $escaper = new LaminasEscaperWrapper( new Escaper('utf-8') );
        $obj = new DataList($escaper, $options);

        $optionVals = $obj->getOptionValues();
        $expected = ['3', '4', '5'];

        $this->assertEquals($expected, $optionVals);
    }
}