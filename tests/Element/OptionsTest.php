<?php


/**
 *
 * @author GBrabyn
 */
class OptionsTest extends PHPUnit_Framework_TestCase  
{
    
    public function testAddAndPrepend()
    {
        $obj = new GBrabyn\DynamicForms\Element\Options();
        
        $value1 = 1;
        $label1 = 'Australia';
        $group1 = 'Oceania';
        $attributes1 = ['id'=>'_1'];
        
        $obj->add($value1, $label1, $group1, $attributes1);

        $option1 = $obj->getIterator()->current();
        
        $this->assertEquals($value1, $option1->value());
        
        $value2 = 'two';
        $label2 = 'NZ';
        $group2 = 'Oceania';
        $attributes2 = ['id'=>'_2'];
        
        $obj->prepend($value2, $label2, $group2, $attributes2);
        
        $option2 = $obj->getIterator()->current();
        
        $this->assertEquals([$option2, $option1], $obj->getOptions());
    }
    
    
    public function testSetFromValuesOnly()
    {
        $values = ['blue', 'green'];
        $obj = new GBrabyn\DynamicForms\Element\Options($values);
        
        $this->assertEquals($values, $obj->getValues());
    }
    
    
    public function testSetFromMultiArray()
    {
        $obj = new GBrabyn\DynamicForms\Element\Options();
        
        $multiArray = [
            ['cid'=>1, 'name'=>'Australia', 'category'=>'Oceania'],
            ['cid'=>2, 'name'=>'NZ', 'category'=>'Oceania'],
            ['cid'=>3, 'name'=>'UK', 'category'=>'Europe'],            
        ];
        
        $obj->setFromMultiArray($multiArray, 'cid', 'name', 'category');
        
        $this->assertEquals([1,2,3], $obj->getValues());
        
        $option1 = $obj->getOptions()[0];
        
        $this->assertEquals(1, $option1->value());
        $this->assertEquals('Australia', $option1->label());
        $this->assertEquals('Oceania', $option1->group());
    }
    
    
    public function testClone()
    {
        $values = ['blue', 'green'];
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $obj1 = new GBrabyn\DynamicForms\Element\Options($values);
        $obj2 = clone $obj1;
        
        $obj1->getOptions()[1]->attributes()->add(['selected']);
        $obj1->add('red', 'red');
        
        $this->assertEquals($values, $obj2->getValues());
        
        $optGreenAttributes = $obj2->getOptions()[1]->attributes();
        $optGreenAttributes->setEscaper($escaper);
        
        $this->assertEquals('', $optGreenAttributes->getAsString());
    }

}
