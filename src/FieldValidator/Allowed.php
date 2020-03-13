<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

/**
 * validates a field contains a value in the allowed list
 *
 * @author GBrabyn
 */
class Allowed extends FieldValidatorAbstract 
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
        return \in_array($this->value, $this->getAllowedStrings(), true);
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
        
        return new Error('Not an approved value', 'inputNotAllowed', []);
    }
}
