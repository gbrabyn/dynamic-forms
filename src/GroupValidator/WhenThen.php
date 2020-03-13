<?php
namespace GBrabyn\DynamicForms\GroupValidator;

use GBrabyn\DynamicForms\GroupValidator\WhenThen AS WT;
use GBrabyn\DynamicForms\Error;

/**
 * Customizable class that applies the ThenInterface class validation rules when 
 * the WhenInterface class conditions are meet.
 *
 * @author GBrabyn
 */
class WhenThen implements GroupValidatorInterface 
{
    /**
     *
     * @var Error 
     */
    private $formError;
    
    /**
     *
     * @var bool
     */
    private $valid = true;
    
    /**
     *
     * @var bool 
     */
    private $validateCalled = false;
    
    /**
     *
     * @var WT\WhenInterface
     */
    private $when;
    
    /**
     *
     * @var WT\ThenInterface
     */
    private $then;

    /**
     * If the WhenInterface conditions are meet then the ThenInterface conditions 
     * are applied to validation, otherwise validates to true.
     * 
     * @param \GBrabyn\DynamicForms\GroupValidator\WhenThen\WhenInterface $when
     * @param \GBrabyn\DynamicForms\GroupValidator\WhenThen\ThenInterface $then
     * @param Error|null $formError
     */
    public function __construct(WT\WhenInterface $when, WT\ThenInterface $then, $formError=null)
    {
        $this->when = $when;
        $this->then = $then;
        $this->formError = $formError;
    }

    /**
     * 
     * @return bool
     */
    public function isValid()
    {
        if($this->validateCalled === false){
            $this->validate();
        }
        
        return $this->valid;
    }

    /**
     * 
     * @return Error|null
     */
    public function getError()
    {
        return $this->formError;
    }

    /**
     * 
     * @return boolean
     */
    private function validate()
    {
        $this->valid = $this->when->conditionApplies() ? $this->then->meetConditions() : true;
        $this->validateCalled = true;
        
        return $this->valid;
    }
}
