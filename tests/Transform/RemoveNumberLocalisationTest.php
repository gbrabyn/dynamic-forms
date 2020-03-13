<?php

/**
 * Description of RemoveNumberLocalisationTest
 *
 * @author GBrabyn
 */
class RemoveNumberLocalisationTest extends PHPUnit_Framework_TestCase  
{
    public function testGetValue()
    {
        $var = new GBrabyn\DynamicForms\Transform\RemoveNumberLocalisation('de_DE');
        
        $var->setValue(' sss   ');
        $this->assertEquals(' sss   ', $var->getValue());
        
        $var->setValue('1.999,02');
        $this->assertEquals(1999.02, $var->getValue());
        
        $var->setValue('1.999');
        $this->assertEquals(1999, $var->getValue());
    }
}
