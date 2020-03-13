<?php
namespace GBrabyn\DynamicForms\ErrorDecorator\html5;

use GBrabyn\DynamicForms\ErrorDecorator\StandAloneErrorAbstract;

/**
 * Shows a field error message separately from the element
 *
 * @author GBrabyn
 */
class Separate extends StandAloneErrorAbstract
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
        return '<span class="error msgSeparate">'.$this->getErrorMsg(0).'</span>';
    }
}
