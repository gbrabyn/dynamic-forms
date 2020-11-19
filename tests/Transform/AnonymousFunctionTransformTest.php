<?php

use PHPUnit\Framework\TestCase;

/**
 *
 * @author GBrabyn
 */
class AnonymousFunctionTransformTest extends TestCase  
{
    
    public function testGetValue()
    {
        $anonymousFunction = function($value){
            return $value.'_zzz';
        };
        
        $obj = new GBrabyn\DynamicForms\Transform\AnonymousFunctionTransform($anonymousFunction);

        $obj->setValue('blue');
        $this->assertEquals('blue_zzz', $obj->getValue());
    }
}
