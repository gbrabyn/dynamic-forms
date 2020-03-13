<?php


/**
 *
 * @author GBrabyn
 */
class TextareaTest extends PHPUnit_Framework_TestCase 
{
    public function testTextarea()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj = new \GBrabyn\DynamicForms\Element\html5\Textarea($escaper);
        
        $field = new \GBrabyn\DynamicForms\Field();
        $field->setValue(' ">zyx;\' </textarea> 123  '.PHP_EOL.'456', false);
        
        $obj->setField($field);
        $obj->setName('bob');
        $attributes = new GBrabyn\DynamicForms\Element\Attributes(['id'=>'xyz', 'class'=>'green paint']);
        $obj->setAttributes($attributes);
        
        $element = (string)$obj;
        $expected = <<<EOT
<textarea name="bob" id="xyz" class="green paint"> &quot;&gt;zyx;&#039; &lt;/textarea&gt; 123  
456</textarea>
EOT;
        $this->assertEquals(trim($expected), $element);
    }
}
