<?php
namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ListAbstract;
use GBrabyn\DynamicForms\Element\Option;

/**
 * A <ul><li> list of radio input fields
 *
 * @author GBrabyn
 */
class RadioList extends ListAbstract
{
    protected function optionItems()
    {
        $ret = [];
        foreach($this->options AS $option){
            /* @var $option Option */

            $attributes = $option->attributes();
            $attributes->setEscaper($this->getEscaper());
            $attributes->add(['value' => $option->value()]);

            if($this->field->isSelected($option->value())){
                $attributes->add(['checked']);
            }

            $radio = '<input type="radio" name="'.$this->fieldName.'" '.$attributes->getAsString().'>';
            
            $ret[] = '<label>'.$radio.$this->escapeHtml($option->label()).'</label>';
        }

        return $ret;
    }
}
