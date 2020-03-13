<?php
namespace GBrabyn\DynamicForms\ErrorDecorator\html5;

use GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract;

/**
 * Places error message on right side of field element
 *
 * @author GBrabyn
 */
class RightSide extends ErrorAbstract
{

    public function __construct($translator=null)
    {
        if($translator){
            $this->setTranslator($translator);
        }
    }
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        $this->exceptionsCheck();
        
        $this->element->getAttributes()->add(['class'=>'error']);
        
        return $this->element->getWithoutErrorMessage().'<span class="error msgRight">'.$this->getErrorMsg(0).'</span>';
    }
}
