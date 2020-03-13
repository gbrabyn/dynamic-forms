<?php

use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class FileTest extends PHPUnit_Framework_TestCase 
{
    
    public function testGetWithoutErrorMessage()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        
        $obj = new GBrabyn\DynamicForms\Element\html5\File($escaper);
        
        $field = new Field();
        $obj->setField($field);
        $obj->setName('bob');
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['id'=>'b123']);
        $obj->setAttributes($attributes);
        
        $element = (string)$obj;
        $expected = <<<EOT
<input type="file" name="bob" id="b123">
EOT;
        $this->assertEquals(trim($expected), $element);
    }
}
