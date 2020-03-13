<?php

use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class HiddenTest extends PHPUnit_Framework_TestCase 
{
    
    public function testIsThereAnySyntaxError()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $var = new GBrabyn\DynamicForms\Element\html5\Hidden($escaper, 'zz');
        $this->assertTrue(is_object($var));
    }
    
    
    public function testGetWithoutErrorMessage()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Hidden($escaper, 'zz');
        $field = new Field();
        
        $obj->setField($field);
        $obj->setName('bob[]');
        
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['required', 'id'=>'b123', 'class'=>'green blue']);
        $attributes->add(['id'=>'xyz', 'class'=>'green paint']);
        
        $obj->setAttributes($attributes);
        
        $element = (string)$obj;
        $expected = <<<EOT
<input type="hidden" name="bob[]" value="zz" id="xyz" class="green blue paint" required>
EOT;
        $this->assertEquals(trim($expected), $element);
    }

    public function testValueFromAttributes()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Hidden($escaper, null);
        $field = new Field();

        $obj->setField($field);
        $obj->setName('bob[]');

        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['value'=>'aaa']);

        $obj->setAttributes($attributes);

        $element = (string)$obj;
        $expected = <<<EOT
<input type="hidden" name="bob[]" value="aaa">
EOT;
        $this->assertEquals(trim($expected), $element);
    }

    public function testDuplicateValueUseThrowException()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Hidden($escaper, 6);
        $field = new Field();

        $obj->setField($field);
        $obj->setName('bob[]');

        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['value'=>'aaa']);

        $obj->setAttributes($attributes);

        $this->expectException(\InvalidArgumentException::class);
        $obj->getWithoutErrorMessage();
    }
}
