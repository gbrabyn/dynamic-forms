<?php
namespace GBrabyn\DynamicForms\FieldValidator;

/**
 *
 * @author GBrabyn
 */
class AnonymousFunctionValidator extends FieldValidatorAbstract
{
    /**
     *
     * @var Closure
     */
    private $function;
    
    /**
     *
     * @var Error 
     */
    private $error;
    
    
    /**
     * 
     * @param Closure $function
     * @param string $errorMessage
     */
    public function __construct(\Closure $function, \GBrabyn\DynamicForms\Error $error)
    {
        $this->function = $function;
        $this->error = $error;
    }
    
    /**
     * 
     * @return bool
     */
    public function isValid()
    {
        $func = $this->function;
        
        return $func($this->value);
    }
    
    /**
     * 
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    
}
