<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class CheckboxTest extends TestCase 
{
    
    public function testGetWithoutErrorMessage()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );

        $obj = new GBrabyn\DynamicForms\Element\html5\Checkbox($escaper, '5');
        $field = new Field();
        $field->setValue(5, false);
        
        $obj->setField($field);
        $obj->setName('bob');
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['id'=>'b123', 'class'=>'green blue']);
        $obj->setAttributes($attributes);
        
        $element1 = (string)$obj;
        $expected1 = <<<EOT
<input type="checkbox" name="bob" value="5" id="b123" class="green blue" checked>
EOT;
        $this->assertEquals(trim($expected1), $element1);
        
        $obj->setName('bob[]');
        $field->setValue([4, 5], false);
        
        $element2 = (string)$obj;
        $expected2 = <<<EOT
<input type="checkbox" name="bob[]" value="5" id="b123" class="green blue" checked>
EOT;
        $this->assertEquals(trim($expected2), $element2);
        
        $field->setValue(null, false);
        
        $element3 = (string)$obj;
        $expected3 = <<<EOT
<input type="checkbox" name="bob[]" value="5" id="b123" class="green blue">
EOT;
        $this->assertEquals(trim($expected3), $element3);
        
        $field->setValue(51, false);
        $element4 = (string)$obj;
        
        $this->assertEquals(trim($expected3), $element4);
    }


    public function testValueFromAttributes()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['value'=>5, 'class'=>'green blue']);
        $field = new Field();
        $field->setValue(5, false);

        $obj = new GBrabyn\DynamicForms\Element\html5\Checkbox($escaper, null);
        $obj->setName('bob');
        $obj->setField($field);
        $obj->setAttributes($attributes);

        $element1 = (string)$obj;
        $expected1 = <<<EOT
<input type="checkbox" name="bob" value="5" class="green blue" checked>
EOT;
        $this->assertEquals(trim($expected1), $element1);

        $field->setValue(6, false);

        $element2 = (string)$obj;
        $expected2 = <<<EOT
<input type="checkbox" name="bob" value="5" class="green blue">
EOT;
        $this->assertEquals(trim($expected2), $element2);
    }

    public function testDuplicateValueUseThrowException()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['value'=>5, 'class'=>'green blue']);
        $field = new Field();
        $field->setValue(5, false);

        $obj = new GBrabyn\DynamicForms\Element\html5\Checkbox($escaper, 6);
        $obj->setName('bob');
        $obj->setField($field);
        $obj->setAttributes($attributes);

        $this->expectException(\InvalidArgumentException::class);
        $obj->getWithoutErrorMessage();
    }
    
}
