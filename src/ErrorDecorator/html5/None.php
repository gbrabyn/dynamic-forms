<?php
namespace GBrabyn\DynamicForms\ErrorDecorator\html5;

use GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract;

/**
 * Used when it is not wanted for error messages to display along side the element. 
 * If field has an error then the element will be highlighted however.
 *
 * @author GBrabyn
 */
class None extends ErrorAbstract
{
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        $this->exceptionsCheck();
        
        $this->element->getAttributes()->add(['class'=>'error']);
        
        return $this->element->getWithoutErrorMessage();
    }
}
