<?php

use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class ElementAbstractTest extends TestCase  
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
