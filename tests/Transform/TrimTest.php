<?php
use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class TrimTest extends TestCase  
{
    public function testIsThereAnySyntaxError()
    {
        $var = new GBrabyn\DynamicForms\Transform\Trim();
        $this->assertTrue(is_object($var));
    }
    
    
    public function testGetValue()
    {
        $var = new GBrabyn\DynamicForms\Transform\Trim();
        $var->setValue(' sss   ');
        
        $this->assertEquals('sss', $var->getValue());
        
        $var->setValue(['blue']);
        $this->assertEquals('', $var->getValue());
    }
}
