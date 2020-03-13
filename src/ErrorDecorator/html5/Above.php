<?php
namespace GBrabyn\DynamicForms\ErrorDecorator\html5;

use GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract;

/**
 *
 * @author GBrabyn
 */
class Above extends ErrorAbstract  
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
        
        return '<span class="error msgAbove">'.$this->getErrorMsg(0).'</span><br>'.$this->element->getWithoutErrorMessage();
    }
}
