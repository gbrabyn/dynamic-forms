<?php
namespace GBrabyn\DynamicForms\GroupValidator;

use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Field;

/**
 * Description of AnonymousFunctionValidator
 *
 * @author GBrabyn
 */
class AnonymousFunctionValidator implements GroupValidatorInterface
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
        
        return $func();
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
