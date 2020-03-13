<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

/**
 * Validates a value is an array with sequential keys - protection from hackers tampering with values
 *
 * @author GBrabyn
 */
class SequentialArray extends FieldValidatorAbstract
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
        if(false === \is_array($this->value)){
            return false;
        }
        
        $x = 0;
        foreach($this->value AS $key => $v){
            if($key !== $x++){
                return false;
            }
        }

        return true;
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
        
        return new Error('Not an array with sequential keys', 'inputNotSequentialArray', []);
    }
}
