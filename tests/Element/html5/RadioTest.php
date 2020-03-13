<?php

use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class RadioTest extends PHPUnit_Framework_TestCase 
{
    
    public function testGetWithoutErrorMessage()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Radio($escaper, '5');
        $field = new Field();
        $field->setValue(5, false);
        
        $obj->setField($field);
        $obj->setName('bob');
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['id'=>'b123']);
        $obj->setAttributes($attributes);
        
        $element1 = (string)$obj;
        $expected1 = <<<EOT
<input type="radio" name="bob" value="5" id="b123" checked>
EOT;
        $this->assertEquals(trim($expected1), $element1);
        
        $field->setValue(51, false);
        $element2 = (string)$obj;
        
        $expected2 = <<<EOT
<input type="radio" name="bob" value="5" id="b123">
EOT;
        $this->assertEquals(trim($expected2), $element2);
    }

    public function testValueFromAttributes()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['value'=>5]);
        $field = new Field();
        $field->setValue(5, false);

        $obj = new GBrabyn\DynamicForms\Element\html5\Radio($escaper, null);
        $obj->setField($field);
        $obj->setName('bob');
        $obj->setAttributes($attributes);

        $element1 = (string)$obj;
        $expected1 = <<<EOT
<input type="radio" name="bob" value="5" checked>
EOT;
        $this->assertEquals(trim($expected1), $element1);

        $field->setValue(6, false);

        $element2 = (string)$obj;
        $expected2 = <<<EOT
<input type="radio" name="bob" value="5">
EOT;
        $this->assertEquals(trim($expected2), $element2);
    }

    public function testDuplicateValueUseThrowException()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['value'=>5]);
        $field = new Field();
        $field->setValue(5, false);

        $obj = new GBrabyn\DynamicForms\Element\html5\Radio($escaper, 6);
        $obj->setField($field);
        $obj->setName('bob');
        $obj->setAttributes($attributes);

        $this->expectException(\InvalidArgumentException::class);
        $obj->getWithoutErrorMessage();
    }
    
}
