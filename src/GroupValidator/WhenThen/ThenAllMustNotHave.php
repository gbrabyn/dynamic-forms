<?php
namespace GBrabyn\DynamicForms\GroupValidator\WhenThen;

use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class ThenAllMustNotHave implements ThenInterface 
{
    private $fields = [];

    /**
     * 
     * @param Field $field
     * @param array $values
     * @param Error $error
     * @return $this
     */
    public function add(Field $field, array $values, Error $error)
    {
        $this->fields[] = ['field'=>$field, 'values'=>$values, 'error'=>$error];
        return $this;
    }
    
    /**
     * @return bool
     */
    public function meetConditions()
    {
        $ret = true;
        
        foreach($this->fields AS $fieldWithValues){
            /* @var $field Field */
            $field = $fieldWithValues['field'];
            $values = $fieldWithValues['values'];
            $error = $fieldWithValues['error'];
            
            if(\in_array($field->getValue(), $values)){
                $field->addError($error);
                $ret = false;
            }
        }
        
        return $ret;
    }
}
