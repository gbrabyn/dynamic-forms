<?php
namespace GBrabyn\DynamicForms\Element;

/**
 * Contains data for 1 select box option or 1 checkbox or radio element option
 *
 * @author GBrabyn
 */
class Option 
{

    private $value;
    
    
    private $label;
    
    
    private $group;
    
    
    private $attributes;
    
    /**
     * 
     * @param scalar $value
     * @param string $label
     * @param string $group
     * @param \GBrabyn\DynamicForms\Element\Attributes $attributes
     */
    public function __construct($value, $label, $group, Attributes $attributes)
    {
        $this->value = $value;
        $this->label = $label;
        $this->group = $group;
        $this->attributes = $attributes;
    }
    
    /**
     * 
     * @return string|int|float|null
     */
    public function value() 
    {
        return $this->value;
    }

    /**
     * 
     * @return string
     */
    public function label() 
    {
        return $this->label;
    }

    /**
     * 
     * @return string
     */
    public function group() 
    {
        return $this->group;
    }

    /**
     * 
     * @return Attributes
     */
    public function attributes() 
    {
        return $this->attributes;
    }
    
    
    public function __clone()
    {
        $this->attributes = clone $this->attributes;
    }

}
