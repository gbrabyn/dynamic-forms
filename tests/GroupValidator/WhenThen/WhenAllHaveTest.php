<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\GroupValidator\WhenThen\WhenAllHave;
use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class WhenAllHaveTest extends TestCase 
{

    public function testConditionApplies()
    {
        $field1 = new Field();
        $field2 = new Field();
        $obj1 = new WhenAllHave();
        $obj1->add($field1, ['1a', '1b']);
        $obj1->add($field2, ['2a', '2b']);
        
        $this->assertFalse($obj1->conditionApplies());
        
        $field1->setValue('3b', false);
        $field2->setValue('4a', false);
        
        $this->assertFalse($obj1->conditionApplies());
        
        $field1->setValue('1b', false);
        $this->assertFalse($obj1->conditionApplies());
        
        $field2->setValue('2a', false);
        $this->assertTrue($obj1->conditionApplies());
    }
    
    
}
