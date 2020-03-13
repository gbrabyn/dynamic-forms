<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

/**
 * Validates value is an integer, float or string, i.e. prevents hackers making an array value
 *
 * @author GBrabyn
 */
class Scalar extends FieldValidatorAbstract
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
        return \is_scalar($this->value);
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
        
        return new Error('Must be a string or number', 'inputNotScalar', []);
    }
}
