<?php

/**
 *
 * @author GBrabyn
 */
class ErrorAbstractTest extends PHPUnit_Framework_TestCase 
{
    public function getClass()
    {
        return $this
                ->getMockBuilder('GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract')
                ->getMockForAbstractClass();
    }
    
    public function getTranslator()
    {
        return $this
                ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
                ->getMock();
    }
    
    
    public function invokeMethod($object, $methodName, array $parameters=[])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $parameters);
    }
    
    
    public function testIsThereAnySyntaxError()
    {
        $var = $this->getClass();
        $this->assertTrue(is_object($var));
    }
    
    
    public function testTranslate()
    {
        $obj = $this->getClass();
        $translator = $this->getTranslator();
        $translator->method('translate')->willReturn('%2d %1s bottles');
        
        $hasTranslator1 = $this->invokeMethod($obj, 'hasTranslator', []);
        
        $this->assertFalse($hasTranslator1);
        
        $this->invokeMethod($obj, 'setTranslator', [$translator]);
        
        $hasTranslator2 = $this->invokeMethod($obj, 'hasTranslator', []);
        
        $this->assertTrue($hasTranslator2);
        
        $output = $this->invokeMethod($obj, 'translate', ['blue']);
        
        $this->assertEquals('%2d %1s bottles', $output);
    }
}
