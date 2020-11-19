<?php
use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms;
use GBrabyn\DynamicForms\Manager\ValidatorManager;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class ValidatorManagerTest extends TestCase 
{

    public function testAllowed()
    {
        $obj = new ValidatorManager();
        
        $this->assertInstanceOf(DynamicForms\FieldValidator\Allowed::class, $obj->allowed(['one']));
        
        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\Allowed::class, $obj->allowed(['one'], $error));
    }
    
    public function testAllowedMultiple()
    {
        $obj = new ValidatorManager();
        
        $this->assertInstanceOf(DynamicForms\FieldValidator\AllowedMultiple::class, $obj->allowedMultiple(['one']));
        
        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\AllowedMultiple::class, $obj->allowedMultiple(['one'], $error));
    }

    public function testArrayCount()
    {
        $obj = new ValidatorManager();
        $this->assertInstanceOf(DynamicForms\FieldValidator\ArrayCount::class, $obj->arrayCount(3, 5));

        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\ArrayCount::class, $obj->arrayCount(0, null, $error));
    }

    public function testColor()
    {
        $obj = new ValidatorManager();
        $this->assertInstanceOf(DynamicForms\FieldValidator\Color::class, $obj->color());

        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\Color::class, $obj->color($error));
    }
    
    public function testRequired()
    {
        $obj = new ValidatorManager();
        $this->assertInstanceOf(DynamicForms\FieldValidator\Required::class, $obj->required());
        
        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\Required::class, $obj->required($error));
    }
    
    public function testInteger()
    {
        $obj = new ValidatorManager();
        $this->assertInstanceOf(DynamicForms\FieldValidator\Integer::class, $obj->integer());
        
        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\Integer::class, $obj->integer($error));
    }
    
    public function testPosInt()
    {
        $obj = new ValidatorManager();
        $this->assertInstanceOf(DynamicForms\FieldValidator\PositiveInteger::class, $obj->posInt());
        
        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\PositiveInteger::class, $obj->posInt($error));
    }

    public function testFloat()
    {
        $obj = new ValidatorManager();
        $this->assertInstanceOf(DynamicForms\FieldValidator\FloatingPointInteger::class, $obj->float());
        
        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\FloatingPointInteger::class, $obj->float($error));
    }
    
    public function testScalar()
    {
        $obj = new ValidatorManager();
        $this->assertInstanceOf(DynamicForms\FieldValidator\Scalar::class, $obj->scalar());
        
        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\Scalar::class, $obj->scalar($error));
    }

    public function testSequentialArray()
    {
        $obj = new ValidatorManager();
        $this->assertInstanceOf(DynamicForms\FieldValidator\SequentialArray::class, $obj->sequentialArray());

        $error = new Error('message', 'key');
        $this->assertInstanceOf(DynamicForms\FieldValidator\SequentialArray::class, $obj->sequentialArray($error));
    }

    public function testMutuallyRequired()
    {
        $obj = new ValidatorManager();
        $fields = [
            (new Field)->setValue('aaa', true), 
            (new Field)->setValue('bbb', false), 
        ];
        $error = new Error('message', 'key');
        
        $this->assertInstanceOf(DynamicForms\GroupValidator\MutuallyRequired::class, $obj->mutuallyRequired($fields));
        $this->assertInstanceOf(DynamicForms\GroupValidator\MutuallyRequired::class, $obj->mutuallyRequired($fields, $error));
    }
    
    public function testCompareNumbers()
    {
        $obj = new ValidatorManager();
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');    
        
        $this->assertInstanceOf(DynamicForms\GroupValidator\CompareNumbers::class, $obj->compareNumbers($subject, $compareWith, '>=', $error));
    }
    
    public function testCompareStrings()
    {
        $obj = new ValidatorManager();
        $subject = new Field();
        $compareWith = new Field();
        $error = new Error('Error Message');    
        
        $this->assertInstanceOf(DynamicForms\GroupValidator\CompareStrings::class, $obj->compareStrings($subject, $compareWith, '>=', $error));
    }

    public function testUnique()
    {
        $obj = new ValidatorManager();
        $fields = [
            (new Field)->setValue('aaa', true), 
            (new Field)->setValue('bbb', false), 
        ];
        $error = new Error('message', 'key');
        
        $this->assertInstanceOf(DynamicForms\GroupValidator\Unique::class, $obj->unique($fields));
        $this->assertInstanceOf(DynamicForms\GroupValidator\Unique::class, $obj->unique($fields, $error));
    }
    
    public function testWhenThen()
    {
        $obj = new ValidatorManager();
        $whenAnyHave = new DynamicForms\GroupValidator\WhenThen\WhenAnyHave();
        $thenOneMustHave = new DynamicForms\GroupValidator\WhenThen\ThenOneMustHave();
        $error = new Error('message', 'key');
        
        $this->assertInstanceOf(DynamicForms\GroupValidator\WhenThen::class, $obj->whenThen($whenAnyHave, $thenOneMustHave));
        $this->assertInstanceOf(DynamicForms\GroupValidator\WhenThen::class, $obj->whenThen($whenAnyHave, $thenOneMustHave, $error));
    }
    
    
}
