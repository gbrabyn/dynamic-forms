<?php

use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Element\Options;

/**
 *
 * @author GBrabyn
 */
class SelectTest extends PHPUnit_Framework_TestCase 
{

    public function testGetWithoutErrorMessage()
    {
        $options = new Options();
        $options->add('', 'month...', null, ['class'=>'faint']);
        $options->add('3', 'March');
        $options->add('4', 'April');
        $options->add('5', 'May');
        
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Select($escaper, $options);
        $field = new Field();
        $field->setValue(5, false);

        $obj->setField($field);
        $obj->setName('bob');
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['required', 'id'=>'b123']);
        $obj->setAttributes($attributes);
        
        $element = (string)$obj;
        $expected = <<<EOT
<select name="bob" id="b123" required><option value="" class="faint">month...</option><option value="3">March</option><option value="4">April</option><option value="5" selected>May</option></select>
EOT;
        $this->assertEquals(trim($expected), $element);
    }
    
    public function testCloning()
    {
        $options = new Options();
        $options->add('', 'month...', null, ['class'=>'faint']);
        $options->add('3', 'March');
        $options->add('4', 'April');
        $options->add('5', 'May');
        
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj1 = new GBrabyn\DynamicForms\Element\html5\Select($escaper, $options);
        $obj2 = clone $obj1;
        
        $field1 = new Field();
        $field1->setValue(5, false);
        $obj1->setField($field1);
        $obj1->setName('bob');
        $attributes1 = new GBrabyn\DynamicForms\Element\Attributes(['required']);
        $obj1->setAttributes($attributes1);
        
        $field2 = new Field();
        $field2->setValue(3, false);
        $obj2->setField($field2);
        $obj2->setName('sue');
        
        $element1 = (string)$obj1;
        $expected1 = <<<EOT
<select name="bob" required><option value="" class="faint">month...</option><option value="3">March</option><option value="4">April</option><option value="5" selected>May</option></select>
EOT;
        $this->assertEquals(trim($expected1), $element1);
        
        $attributes2 = new GBrabyn\DynamicForms\Element\Attributes([]);
        $obj2->setAttributes($attributes2);
        
        $element2 = (string)$obj2;
        $expected2 = <<<EOT
<select name="sue" ><option value="" class="faint">month...</option><option value="3" selected>March</option><option value="4">April</option><option value="5">May</option></select>
EOT;
        $this->assertEquals(trim($expected2), $element2);
    }
    
    
    public function testGetOptionValues()
    {
        $options = new Options();
        $options->add('', 'month...', null, []);
        $options->add('3', 'March');
        $options->add('4', 'April');
        $options->add('5', 'May');
        
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Select($escaper, $options);
        
        $optionVals = $obj->getOptionValues();
        $expected = ['', '3', '4', '5'];
        
        $this->assertEquals($expected, $optionVals);
    }
    
    
    public function testWorksAsMultiple()
    {
        $options = new Options();
        $options->add('', 'month...', null, []);
        $options->add('3', 'March');
        $options->add('4', 'April');
        $options->add('5', 'May');
        
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Select($escaper, $options);
        $field = new Field();
        $field->setValue(['3','5'], false);

        $obj->setField($field);
        $obj->setName('bob[]');
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['multiple']);
        $obj->setAttributes($attributes);
        
        $element = (string)$obj;
        $expected = <<<EOT
<select name="bob[]" multiple><option value="">month...</option><option value="3" selected>March</option><option value="4">April</option><option value="5" selected>May</option></select>
EOT;
        $this->assertEquals(trim($expected), $element);
    }
    
    
    public function testOptGroups1()
    {
        $options = new Options();
        $options->add('', 'Choose country...', null, []);
        $options->add(22, 'Egypt', 'Africa', []);
        $options->add('25', 'Libya', 'Africa', []);
        $options->add(26, 'Morocco', 'Africa', []);
        $options->add('allAfrica', 'All Africa', null, []);
        $options->add('1', 'China', 'Asia', []);
        $options->add('68', 'Japan', 'Asia', []);
        $options->add('allAsia', 'All Asia', null, []);
        $options->add('other', 'Not listed', null, []);
        
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Select($escaper, $options);
        $field = new Field();
        $field->setValue(5, false);

        $obj->setField($field);
        $obj->setName('bob');
        
        $attributes = new GBrabyn\DynamicForms\Element\Attributes();
        $obj->setAttributes($attributes);
        
        $element = (string)$obj;
        $expected = <<<EOT
<select name="bob" ><option value="">Choose country...</option><optgroup label="Africa"><option value="22">Egypt</option><option value="25">Libya</option><option value="26">Morocco</option></optgroup><option value="allAfrica">All Africa</option><optgroup label="Asia"><option value="1">China</option><option value="68">Japan</option></optgroup><option value="allAsia">All Asia</option><option value="other">Not listed</option></select>
EOT;
        $this->assertEquals(trim($expected), $element);
    }
    
    
    public function testOptGroups2()
    {
        $options = new Options();
        $options->add(22, 'Egypt', 'Africa', []);
        $options->add('25', 'Libya', 'Africa', []);
        $options->add(26, 'Morocco', 'Africa', []);
        $options->add('1', 'China', 'Asia', []);
        $options->add('68', 'Japan', 'Asia', []);
        
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new GBrabyn\DynamicForms\Element\html5\Select($escaper, $options);
        $field = new Field();
        $field->setValue(25, false);

        $obj->setField($field);
        $obj->setName('bob[]');
        
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['required', 'id'=>'b123', 'multiple']);
        $obj->setAttributes($attributes);
        
        $element = (string)$obj;
        $expected = <<<EOT
<select name="bob[]" id="b123" required multiple><optgroup label="Africa"><option value="22">Egypt</option><option value="25" selected>Libya</option><option value="26">Morocco</option></optgroup><optgroup label="Asia"><option value="1">China</option><option value="68">Japan</option></optgroup></select>
EOT;
        $this->assertEquals(trim($expected), $element);
    }

}
