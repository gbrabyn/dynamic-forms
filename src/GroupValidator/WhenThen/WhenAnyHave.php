<?php
namespace GBrabyn\DynamicForms\GroupValidator\WhenThen;

use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
class WhenAnyHave implements WhenInterface
{
    private $fields = [];

    /**
     * 
     * @param Field $field
     * @param array $values
     * @return $this
     */
    public function add(Field $field, array $values)
    {
        $this->fields[] = ['field'=>$field, 'values'=>$values];
        return $this;
    }
    
    /**
     * @return bool
     */
    public function conditionApplies()
    {
        foreach($this->fields AS $fieldWithValues){
            /* @var $field Field */
            $field = $fieldWithValues['field'];
            $values = $fieldWithValues['values'];
            
            if(\in_array($field->getValue(), $values)){
                return true;
            }
        }
        
        return false;
    }
}
