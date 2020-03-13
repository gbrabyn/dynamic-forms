<?php

/**
 * Description of LaminasFilterWrapperTest
 *
 * @author GBrabyn
 */
class LaminasFilterWrapperTest extends PHPUnit_Framework_TestCase
{
    
    public function testGetValue()
    {
        $laminasFilter = new \Laminas\Filter\StripTags();
        
        $obj = new GBrabyn\DynamicForms\Transform\LaminasFilterWrapper($laminasFilter);
        $obj->setValue('String with a <a href="/url">link</a> and other text.');
        
        $this->assertEquals('String with a link and other text.', $obj->getValue());
    }
    
    
    
}
