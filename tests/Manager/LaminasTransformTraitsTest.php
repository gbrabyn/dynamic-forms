<?php

use GBrabyn\DynamicForms\Manager\LaminasTransformTraits;
use GBrabyn\DynamicForms\Transform\LaminasFilterWrapper;

/**
 *
 * @author GBrabyn
 */
class LaminasTransformTraitsTest extends PHPUnit_Framework_TestCase
{

    public function testStripTags()
    {
        $mock = $this->getMockForTrait(LaminasTransformTraits::class);
        
        $this->assertInstanceOf(LaminasFilterWrapper::class, $mock->stripTags([]));
    }
}
