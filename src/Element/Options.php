<?php
namespace GBrabyn\DynamicForms\Element;

/**
 * Holds the options for use in Select boxes, radio buttons, checkbox lists, etc
 *
 * @author GBrabyn
 */
class Options implements \IteratorAggregate 
{
    /**
     *
     * @var array - stores Option instances
     */
    private $options = [];
    
    /**
     * 
     * @param array|null $values - optionally fill with values only options on instantiation
     */
    public function __construct($values=null)
    {
        if(\is_array($values)){
            $this->setFromValuesOnly($values);
        }
    }

    /**
     * 
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator( $this->options );
    }

    /**
     * 
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * 
     * @return array
     */
    public function getValues()
    {
        $values = [];
        
        foreach($this->options AS $option){
            $values[] = $option->value();
        }
        
        return $values;
    }
    
    /**
     * 
     * @param scalar $value
     * @param string $label
     * @param string $group
     * @param array $attributes - "attributeName => value" pairs
     * @return \GBrabyn\DynamicForms\Element\Option
     */
    private function makeOption($value, $label, $group, array $attributes)
    {
        $elAttributes = new Attributes($attributes);
        
        return new Option($value, $label, $group, $elAttributes);
    }
    
    /**
     * 
     * @param scalar $value
     * @param string $label
     * @param string $group
     * @param array $attributes - "attributeName => value" pairs
     * @return $this
     */
    public function add($value, $label, $group=null, array $attributes=[])
    {
        $this->options[] = $this->makeOption($value, $label, $group, $attributes);
        
        return $this;
    }
    
    /**
     * 
     * @param scalar $value
     * @param string $label
     * @param string $group
     * @param array $attributes - "attributeName => value" pairs
     * @return $this
     */
    public function prepend($value, $label, $group=null, array $attributes=[])
    {
        \array_unshift($this->options, $this->makeOption($value, $label, $group, $attributes) );
        
        return $this;
    }
    
    /**
     * 
     * @param array $multiArray
     * @param string $valueKey
     * @param string $labelKey
     * @param string $groupKey
     * @return $this
     */
    public function setFromMultiArray(array $multiArray, $valueKey, $labelKey, $groupKey=null)
    {
        foreach($multiArray AS $item){
            $array = (array)$item;
            $group = $groupKey ? $array[$groupKey] : null;
            
            $this->add($array[$valueKey], $array[$labelKey], $group);
        }
        
        return $this;
    }
    
    /**
     * 
     * @param array $values
     * @return $this
     */
    public function setFromValuesOnly(array $values)
    {
        foreach($values AS $v){
            $this->add($v, $v);
        }
        
        return $this;
    }
    
    
    public function __clone()
    {
        for($i=0, $c=\count($this->options); $i<$c; $i++){
            $this->options[$i] = clone $this->options[$i];
        }
    }
    
    
}
