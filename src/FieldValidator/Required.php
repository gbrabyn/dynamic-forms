<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

/**
 * Makes field entry compulsory
 *
 * @author GBrabyn
 */
class Required extends FieldValidatorAbstract
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
     * Should the validator be used when the field value is null or empty string
     * 
     * @return boolean
     */
    public function useWhenEmpty()
    {
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function isValid()
    {
        return !($this->value === '' || $this->value === null);
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
        
        return new Error('Required', 'inputRequired', []);
    }
}
