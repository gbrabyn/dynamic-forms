<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

/**
 * Validates value is an integer
 *
 * @author GBrabyn
 */
class Integer extends FieldValidatorAbstract
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
        return \filter_var($this->value, \FILTER_VALIDATE_INT) !== false;
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
        
        return new Error('Must be an integer', 'inputNotInteger', []);
    }
}
