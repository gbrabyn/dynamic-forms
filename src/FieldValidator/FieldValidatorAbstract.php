<?php
namespace GBrabyn\DynamicForms\FieldValidator;

/**
 *
 * @author GBrabyn
 */
abstract class FieldValidatorAbstract
{
    /**
     *
     * @var mixed
     */
    protected $value;

    /**
     * 
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * Should the validator be used when the field value is null or empty string.
     * Defaults to false.
     * 
     * @return boolean
     */
    public function useWhenEmpty()
    {
        return false;
    }
    
    /**
     * 
     * @return bool
     */
    abstract public function isValid();

    
    /**
     * 
     * @return Error
     */
    abstract public function getError();
    
    
    
}
