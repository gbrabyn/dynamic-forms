<?php

use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class AllowedTest extends TestCase  
{
    public function testIsValid1()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\Allowed(['', 0, false, null, 1, 3, 'four', true]);

        $obj->setValue('');
        $this->assertTrue($obj->isValid());
        
        $obj->setValue((string)0);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue((string)false);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue((string)null);
        $this->assertTrue($obj->isValid());    
        
        $obj->setValue((string)1);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue((string)3);
        $this->assertTrue($obj->isValid());
        
        $obj->setValue('four');
        $this->assertTrue($obj->isValid());
        
        $obj->setValue((string)true);
        $this->assertTrue($obj->isValid());
    }
    
    
    public function testIsValid2()
    {
        $allowed1 = new GBrabyn\DynamicForms\FieldValidator\Allowed(['dfasf', -1]);
        $allowed1->setValue('');
        $this->assertFalse($allowed1->isValid());
        
        $allowed2 = new GBrabyn\DynamicForms\FieldValidator\Allowed(['']);
        $allowed2->setValue('0');
        $this->assertFalse($allowed2->isValid());
        
        $allowed3 = new GBrabyn\DynamicForms\FieldValidator\Allowed(['']);
        $allowed3->setValue('1');
        $this->assertFalse($allowed3->isValid());

        $allowed4 = new GBrabyn\DynamicForms\FieldValidator\Allowed(['def']);
        $allowed4->setValue('1');
        $this->assertFalse($allowed4->isValid());
    }
}
