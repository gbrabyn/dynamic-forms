<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class InputNumberTest extends TestCase 
{
    
    public function testGetWithoutErrorMessage()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\InputNumber($escaper, 'de_DE');
        $obj->setAttributes(new GBrabyn\DynamicForms\Element\Attributes());
        $field = new Field();
        $field->setValue(' ">zyx;\' ', false);
        
        $obj->setField($field);
        $obj->setName('bob');
        
        $element = (string)$obj;
        $expected1 = <<<EOT
<input type="number" name="bob" value="&#x20;&quot;&gt;zyx&#x3B;&#x27;&#x20;" >
EOT;
        $this->assertEquals(trim($expected1), $element);
        
        $field->setValue(1234.567, false);
        $expected2 = <<<EOT
<input type="number" name="bob" value="1.234,567" >
EOT;
        
        $this->assertEquals(trim($expected2), (string)$obj);
    }
}
