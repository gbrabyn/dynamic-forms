<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Element\Options;

/**
 *
 * @author GBrabyn
 */
class RadioListTest extends TestCase
{
    
    public function testGetWithoutErrorMessage()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $options = new Options();
        $options->add(null, 'None', null, ['class'=>'faint']);
        $options->add('3', 'March');
        $options->add('4', 'April');
        $options->add('5', 'May');  
        
        $obj = new GBrabyn\DynamicForms\Element\html5\RadioList($escaper, $options);
        
        $field = new Field();
        $field->setValue(3, false);

        $obj->setField($field);
        $obj->setName('bob');
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['id'=>'b123']);
        $obj->setAttributes($attributes);
        
        $obj2 = clone $obj;
        
        $field2 = new Field();
        $field2->setValue(4, false);

        $obj2->setField($field2);
        $obj2->setName('sue');
        $attributes2 = new GBrabyn\DynamicForms\Element\Attributes(['id'=>'c456']);
        $obj2->setAttributes($attributes2);

        $element = (string)$obj;
        $expected = <<<EOT
<ul id="b123"><li><label><input type="radio" name="bob" value="" class="faint">None</label></li><li><label><input type="radio" name="bob" value="3" checked>March</label></li><li><label><input type="radio" name="bob" value="4">April</label></li><li><label><input type="radio" name="bob" value="5">May</label></li></ul>
EOT;
        $this->assertEquals(trim($expected), $element);
        
        $element2 = (string)$obj2;
        $expected2 = <<<EOT
<ul id="c456"><li><label><input type="radio" name="sue" value="" class="faint">None</label></li><li><label><input type="radio" name="sue" value="3">March</label></li><li><label><input type="radio" name="sue" value="4" checked>April</label></li><li><label><input type="radio" name="sue" value="5">May</label></li></ul>
EOT;
        $this->assertEquals(trim($expected2), $element2);
    }
    
}
