<?php

use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class AllowedMultipleTest extends TestCase 
{
    public function testIsValid()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\AllowedMultiple(['', 0, false, null, 1, 3, 'four', true]);

        $obj->setValue('');
        $this->assertFalse($obj->isValid());    // not an array
        
        $obj->setValue((string)0);
        $this->assertFalse($obj->isValid());    // not an array
        
        $obj->setValue('four');
        $this->assertFalse($obj->isValid());    // not an array
        
        $obj->setValue([(string)false, (string)null, 'four']);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue([(string)null]);
        $this->assertTrue($obj->isValid());    
        
        $obj->setValue([(string)1, (string)3, (string)true]);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue([(string)3, 'fake', 'four']);
        $this->assertFalse($obj->isValid());
    }
    
    

}
