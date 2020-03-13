<?php
// GBrabyn@w0wq:/var/www/DynamicForms$ vendor/bin/phpunit

/**
 *
 * @author GBrabyn
 */
class MessageTest extends PHPUnit_Framework_TestCase
{
	
    /**
    * Just check if the YourClass has no syntax error 
    *
    * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
    * any typo before you even use this library in a real project.
    *
    */
    public function testIsThereAnySyntaxError()
    {
        $var = new GBrabyn\DynamicForms\Message(null);
        $this->assertTrue(is_object($var));
        unset($var);
    }
  
    /**
    * Just check if the YourClass has no syntax error 
    *
    * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
    * any typo before you even use this library in a real project.
    *
    */
    public function testGetMessage()
    {
        $var = new GBrabyn\DynamicForms\Message('Message1');
        $this->assertTrue($var->getMessage() == 'Message1');
        unset($var);
    }
  
    public function testSetArgs()
    {
        $var = new GBrabyn\DynamicForms\Message(null, null, ['tall'=>'boy']);
        $var->setArgs(['blue'=>'house']);
        
        $this->assertTrue($var->getArgs() == ['blue'=>'house']);
    }
    
    
    public function testGetTranslationKey() 
    {
        $var = new GBrabyn\DynamicForms\Message('message', 'transKey');
        
        $this->assertEquals('transKey', $var->getTranslationKey());
    }
    
    
    public function testGetArgs()
    {
        $var = new GBrabyn\DynamicForms\Message('message', 'transKey', ['blue'=>'house']);
        
        $this->assertEquals(['blue'=>'house'], $var->getArgs());
    }
}
