<?php
namespace GBrabyn\DynamicForms\GroupValidator;

use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 * Used to validate that all Fields have unique values.
 * @author GBrabyn
 */
class Unique implements GroupValidatorInterface
{
    /**
     *
     * @var Error 
     */
    private $error;
    
    /**
     *
     * @var bool
     */
    private $valid = true;
    
    
    private $validateCalled = false;

    
    private $fields = [];
    
    /**
     * 
     * @param Field[] $fields
     * @param Error $error
     */
    public function __construct(array $fields, Error $error=null)
    {
        $this->error = $error;
        
        foreach($fields AS $field){
            $this->addField($field);
        }
    }
    
    
    private function addField(\GBrabyn\DynamicForms\Field $field)
    {
        $this->fields[] = $field;
    }
    
    
    private function validate()
    {
        $values = [];

        foreach($this->fields AS $field){
            $value = $field->getValue();

            if( $value !== '' && $value !== null ){
                $values[$value][] = $field;
            }
        }

        foreach($values AS $value => $fields){
            if(\count($fields) === 1){
                continue;
            }

            $this->valid = false;

            foreach($fields AS $field){
                $field->addError( $this->getFieldError() );
            }
        }

        $this->validateCalled = true;
    }
    
    /**
     * 
     * @return \Error
     */
    private function getFieldError()
    {
        if($this->error){
            return $this->error;
        }
        
        return new Error('Duplicate', 'inputDuplicated', []);
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
        return null;
    }
}
