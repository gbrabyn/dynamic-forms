<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

/**
 * Validates value is an integer zero or greater
 *
 * @author GBrabyn
 */
class PositiveInteger extends FieldValidatorAbstract 
{
    /**
     *
     * @var Error 
     */
    private $error;
    
    
    public function __construct(Error $error=null)
    {
        $this->error = $error;
    }

    /**
     * 
     * @return bool
     */
    public function isValid()
    {
        return (    \filter_var($this->value, \FILTER_VALIDATE_INT) !== false 
                &&  $this->value >= 0
               );
    }
    
    /**
     * 
     * @return Error
     */
    public function getError()
    {
        if($this->error){
            return $this->error;
        }
        
        return new Error('Must be a positive integer', 'inputNotPositiveInteger', []);
    }
}
