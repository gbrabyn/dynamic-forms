<?php

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\Manager\LaminasTransformTraits;
use GBrabyn\DynamicForms\Transform\LaminasFilterWrapper;

/**
 *
 * @author GBrabyn
 */
class LaminasTransformTraitsTest extends TestCase
{

    public function testStripTags()
    {
        $mock = $this->getMockForTrait(LaminasTransformTraits::class);
        
        $this->assertInstanceOf(LaminasFilterWrapper::class, $mock->stripTags([]));
    }
}
