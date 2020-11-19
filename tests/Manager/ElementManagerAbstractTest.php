<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\Manager\ElementManagerAbstract;
use GBrabyn\DynamicForms\Element\LaminasEscaperWrapper;
use Laminas\Escaper\Escaper as LaminasEscaper;
use GBrabyn\DynamicForms\Element\Options;

/**
 *
 * @author GBrabyn
 */
class ElementManagerAbstractTest extends TestCase 
{
    public function getClass()
    {
        $obj = $this
                ->getMockBuilder(ElementManagerAbstract::class)
                ->getMockForAbstractClass();
        
        $laminasEscaper = new LaminasEscaper('utf-8');
        $escaperWrapper = new LaminasEscaperWrapper($laminasEscaper);
        
        $this->setProtectedProperty($obj, 'escaper', $escaperWrapper);
        
        return $obj;
    }
    
    public function invokeMethod($object, $methodName, array $parameters=[])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $parameters);
    }
    
    public function setProtectedProperty($object, $propertyName, $value)
    {
        $reflector = new ReflectionProperty(get_class($object), $propertyName);
        $reflector->setAccessible(true);
        $reflector->setValue($object, $value);
    }
    
    public function testIsThereAnySyntaxError()
    {
        $var = $this->getClass();
        $this->assertTrue(is_object($var));
    }
    
    public function testSetDocTypeThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['xyz']);
    }

    public function testCallback()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);

        $callBackElement = $obj->callback(function($fieldName, $field, $attributes, $escaper){
            return '<div '.$attributes->getAsString().' name="'.$escaper->escapeAttr($fieldName).'">'.$escaper->escapeHtml($field->getValue()).'</div>';
        });

        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\Callback::class, $callBackElement);
    }

    public function testCheckbox()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $checkbox = $obj->checkbox('true');
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\Checkbox::class, $checkbox);   
    }
    
    public function testCheckboxList()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $options = new Options(['one', 'two', 'three']);
        $checkboxList = $obj->checkboxList($options);
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\checkboxList::class, $checkboxList);
    }

    public function testDataList()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);

        $options = new Options(['one', 'two', 'three']);
        $dataList = $obj->datalist($options);

        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\DataList::class, $dataList);
    }

    public function testFile()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $file = $obj->file();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\File::class, $file);   
    }

    public function testHidden()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $hidden = $obj->hidden('xyz');
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\Hidden::class, $hidden);   
    }
    
    public function testInput()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $input = $obj->input('hidden');
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\Input::class, $input);   
    }

    public function testInputNumber()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $inputNumber = $obj->inputNumber('de_DE');
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\inputNumber::class, $inputNumber);   
    }
    
    public function testRadio()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $radio = $obj->radio('2');
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\Radio::class, $radio);   
    }
    
    public function testRadioList()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $options = new Options(['one', 'two', 'three']);
        $radioList = $obj->radioList($options);
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\radioList::class, $radioList);
    }
    
    public function testSelect()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $options = new Options(['one', 'two', 'three']);
        $select = $obj->select($options);
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\select::class, $select);
    }
    
    public function testTextarea()
    {
        $obj = $this->getClass();
        $this->invokeMethod($obj, 'setDocType', ['HTML5']);
        
        $textarea = $obj->textarea();
        
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\Textarea::class, $textarea);   
    }
    
}
