<?php
namespace GBrabyn\DynamicForms\GroupValidator\WhenThen;

use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 * @author GBrabyn
 */
class ThenOneMustNotHave implements ThenInterface  
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
        $ret = false;
        
        foreach($this->fields AS $fieldWithValues){
            /* @var $field Field */
            $field = $fieldWithValues['field'];
            $values = $fieldWithValues['values'];
            
            if(! \in_array($field->getValue(), $values)){
                $ret = true;
            }
        }
        
        if($ret === false){
            foreach($this->fields AS $fieldWithValues){
                /* @var $field Field */
                $field = $fieldWithValues['field'];
                $error = $fieldWithValues['error'];

                $field->addError($error);
            }
        }
        
        return $ret;
    }
}
