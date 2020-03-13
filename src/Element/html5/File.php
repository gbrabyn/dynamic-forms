<?php
namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ElementAbstract;
use GBrabyn\DynamicForms\Element\EscaperInterface;

/**
 * Produces an HTML <input type="file"> upload box
 *
 * @author GBrabyn
 */
class File extends ElementAbstract
{
    
    /**
     * 
     * @return string
     */
    public function getWithoutErrorMessage()
    {
        return '<input type="file" name="'.$this->fieldName.'" '.$this->getAttributesString().'>';
    }
}
