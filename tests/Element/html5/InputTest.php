<?php

use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class InputTest extends PHPUnit_Framework_TestCase 
{
    public function testIsThereAnySyntaxError()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $var = new GBrabyn\DynamicForms\Element\html5\Input($escaper, 'text');
        $this->assertTrue(is_object($var));
    }
    
    
    public function testGetWithoutErrorMessage()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Input($escaper, 'text');
        $field = new Field();
        $field->setValue(' ">zyx;\' ', false);
        
        $obj->setField($field);
        $obj->setName('bob[]');
        
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['required', 'id'=>'b123', 'class'=>'green blue']);
        $attributes->add(['id'=>'xyz', 'class'=>'green paint']);
        
        $obj->setAttributes($attributes);
        
        $element = (string)$obj;
        $expected = <<<EOT
<input type="text" name="bob[]" value="&#x20;&quot;&gt;zyx&#x3B;&#x27;&#x20;" id="xyz" class="green blue paint" required>
EOT;
        $this->assertEquals(trim($expected), $element);
    }
}
