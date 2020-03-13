<?php
namespace GBrabyn\DynamicForms\ErrorDecorator\html5;

use GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract;

/**
 *
 * @author GBrabyn
 */
class Below extends ErrorAbstract 
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
        
        return $this->element->getWithoutErrorMessage().'<br><span class="error msgBelow">'.$this->getErrorMsg(0).'</span>';
    }
}
