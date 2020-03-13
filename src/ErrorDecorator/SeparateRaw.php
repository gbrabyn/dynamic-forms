<?php
namespace GBrabyn\DynamicForms\ErrorDecorator;

use GBrabyn\DynamicForms\TranslatorInterface;

/**
 * Shows a field error message separately from the form element and without any decorating HTML
 *
 * @author GBrabyn
 */
class SeparateRaw extends StandAloneErrorAbstract
{
    /**
     * 
     * @param TranslatorInterface|null $translator
     */
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
        return $this->getErrorMsg(0);
    }
}
