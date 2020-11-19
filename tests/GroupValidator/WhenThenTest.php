<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\GroupValidator\WhenThen;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class WhenThenTest extends TestCase 
{

    public function getWhen()
    {
        return $this
                ->getMockBuilder(WhenThen\WhenInterface::class)
                ->getMock();
    }
    
    public function getThen()
    {
        return $this
                ->getMockBuilder(WhenThen\ThenInterface::class)
                ->getMock();
    }
    
    public function testIsValid()
    {
        $whenFalse = $this->getWhen();
        $whenFalse->method('conditionApplies')->willReturn(false);
        
        $whenTrue = $this->getWhen();
        $whenTrue->method('conditionApplies')->willReturn(true);
        
        $thenFalse = $this->getThen();
        $thenFalse->method('meetConditions')->willReturn(false);
        
        $thenTrue = $this->getThen();
        $thenTrue->method('meetConditions')->willReturn(true);

        
        $obj1 = new WhenThen($whenFalse, $thenFalse);
        $this->assertTrue($obj1->isValid());
        $this->assertNull($obj1->getError());
        
        $obj2 = new WhenThen($whenTrue, $thenFalse);
        $this->assertFalse($obj2->isValid());
        $this->assertNull($obj2->getError());
        
        $obj3 = new WhenThen($whenFalse, $thenTrue);
        $this->assertTrue($obj3->isValid());
        
        $obj4 = new WhenThen($whenTrue, $thenTrue);
        $this->assertTrue($obj4->isValid());
    }
    
    public function testIsValidOnRealInstances()
    {
        $whenAnyHave = new WhenThen\WhenAnyHave();
        $thenOneMustHave = new WhenThen\ThenOneMustHave();
        
        $fieldWhen1 = new Field();
        $fieldWhen2 = new Field();
        
        $whenAnyHave->add($fieldWhen1, [1, '2']);
        $whenAnyHave->add($fieldWhen2, [3, '4']);

        $fieldThen1 = new Field();
        $fieldThen2 = new Field();
        $error1 = new Error('fieldThen1 Error');
        $error2 = new Error('fieldThen2 Error');
        
        $thenOneMustHave->add($fieldThen1, [5, '6'], $error1);
        $thenOneMustHave->add($fieldThen2, [7, '8'], $error2);
        
        $obj1 = new WhenThen($whenAnyHave, $thenOneMustHave);
        $this->assertTrue($obj1->isValid());
        
        $fieldWhen1->setValue(3, false);
        $fieldWhen2->setValue(2, false);
        $fieldThen1->setValue(0, false);
        $fieldThen2->setValue('2', false);
        
        $obj2 = new WhenThen($whenAnyHave, $thenOneMustHave);
        $this->assertTrue($obj2->isValid());
        
        $fieldWhen1->setValue(2, false);
        
        $obj3 = new WhenThen($whenAnyHave, $thenOneMustHave);
        $this->assertFalse($obj3->isValid());
        
        $fieldThen2->setValue('7', false);
        
        $obj4 = new WhenThen($whenAnyHave, $thenOneMustHave);
        $this->assertTrue($obj4->isValid());
    }
    
    public function testErrors()
    {
        $whenFalse = $this->getWhen();
        $whenFalse->method('conditionApplies')->willReturn(false);

        $thenFalse = $this->getThen();
        $thenFalse->method('meetConditions')->willReturn(false);

        $error = new Error('blue', 'paint', ['{type}'=>'balls']);
        
        $obj1 = new WhenThen($whenFalse, $thenFalse);
        $this->assertNull($obj1->getError());

        $obj2 = new WhenThen($whenFalse, $thenFalse, $error);
        $this->assertEquals($error, $obj2->getError());
    }
    
    
}
