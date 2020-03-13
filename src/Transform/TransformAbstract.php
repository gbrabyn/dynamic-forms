<?php
namespace GBrabyn\DynamicForms\Transform;

/**
 *
 * @author GBrabyn
 */
abstract class TransformAbstract
{
    protected $value;
    
    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * 
     * @return mixed
     */
    abstract public function getValue();
}
