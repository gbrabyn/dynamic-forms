<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

/**
 * Used to validate that form array fields (numeric arrays NOT associative arrays)
 * contain values in the allowed list
 *
 * @author GBrabyn
 */
class AllowedMultiple extends FieldValidatorAbstract
{
    /**
     *
     * @var array 
     */
    private $allowed;
    
    /**
     *
     * @var Error 
     */
    private $error;

    /**
     * 
     * @param array $allowed
     * @param \GBrabyn\DynamicForms\Error $error
     */
    public function __construct(array $allowed, Error $error=null)
    {
        $this->allowed = $allowed;
        $this->error = $error;
    }

    /**
     * 
     * @return bool
     */
    public function isValid()
    {
        $values = $this->value;
        
        if(! \is_array($values)){
            return false;
        }
        
        foreach($values AS $v){
            if(! \in_array($v, $this->getAllowedStrings(), true)){
                return false;
            }
        }
        
        return true;
    }
    
    
    private function getAllowedStrings()
    {
        $allowedStrings = [];
        
        foreach($this->allowed AS $v){
            $allowedStrings[] = (string)$v;
        }
        
        return $allowedStrings;
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
        
        return new Error('Contains a value not approved', 'inputNotAllowedValues', []);
    }
}
