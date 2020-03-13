<?php
namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ElementAbstract;

/**
 * produces an HTML textarea
 *
 * @author GBrabyn
 */
class Textarea extends ElementAbstract 
{
    /**
     * 
     * @return string
     */
    public function getWithoutErrorMessage()
    {
        return '<textarea name="'.$this->fieldName.'" '.$this->getAttributesString().'>'.$this->escapeHtml( $this->field->getValue() ).'</textarea>';
    }
}
