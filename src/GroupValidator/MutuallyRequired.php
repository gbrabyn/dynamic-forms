<?php
namespace GBrabyn\DynamicForms\GroupValidator;

use \GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Field;

/**
 * Used to validate when one field in $fields is filled in then all other fields in $fields must also be filled in. 
 * I.e. either none or all the fields must be filled in to validate
 *
 * @author GBrabyn
 */
class MutuallyRequired implements GroupValidatorInterface
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
        $filled = [];
        $empty = [];

        foreach($this->fields AS $field){
            $value = $field->getValue();

            if( $value === '' || $value === null ){
                $empty[] = $field;
            }else{
                $filled[] = $field;
            } 
        }

        if(\count($filled) && \count($empty)){
            $this->valid = false;

            foreach($empty AS $field){
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
        
        return new Error('Required', 'inputRequired', []);
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
