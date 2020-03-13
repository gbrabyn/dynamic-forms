<?php

/**
 *
 * @author GBrabyn
 */
class EmptyMakeNullTest extends PHPUnit_Framework_TestCase  
{
    public function testGetValue()
    {
        $var = new GBrabyn\DynamicForms\Transform\EmptyStringToNull();
        
        $var->setValue(' sss   ');
        $this->assertEquals(' sss   ', $var->getValue());
        
        $var->setValue(0);
        $this->assertEquals(0, $var->getValue());
        
        $var->setValue(false);
        $this->assertEquals(false, $var->getValue());
        
        $var->setValue('');
        $this->assertNull($var->getValue());
        
        $var->setValue(null);
        $this->assertNull($var->getValue());
    }
}
