<?php

/**
 *
 * @author GBrabyn
 */
class ElementAbstractTest extends PHPUnit_Framework_TestCase  
{
    public function getClass()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );

        return $this
                ->getMockBuilder('GBrabyn\DynamicForms\Element\ElementAbstract')
                ->setConstructorArgs( [$escaper] )
                ->getMockForAbstractClass();
    }
    
    
    public function testIsThereAnySyntaxError()
    {
        $var = $this->getClass();
        $this->assertTrue(is_object($var));
    }
}
