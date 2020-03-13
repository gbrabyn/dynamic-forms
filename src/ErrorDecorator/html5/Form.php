<?php
namespace GBrabyn\DynamicForms\ErrorDecorator\html5;

use GBrabyn\DynamicForms\ErrorDecorator\StandAloneErrorAbstract;

/**
 * Used to show if errors in form and any set of form general messages
 *
 * @author GBrabyn
 */
class Form extends StandAloneErrorAbstract
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
        $ret = '<p class="errors">'.\PHP_EOL;
        
        if($this->hasTranslator()){
            $ret .= $this->translate('formErrors').\PHP_EOL;
        }else{
            $ret .= 'Please fix the errors in the form below.'.\PHP_EOL;
        }

        if(\count($this->errors) > 0){
            $ret .= '<br>'.\implode('<br>'.\PHP_EOL, $this->getErrorMessages()).\PHP_EOL;
        }

        $ret .= '</p>';
        
        return $ret;
    }
}
