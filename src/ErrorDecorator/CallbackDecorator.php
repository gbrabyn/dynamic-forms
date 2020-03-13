<?php
namespace GBrabyn\DynamicForms\ErrorDecorator;

use GBrabyn\DynamicForms\{Element\ElementAbstract, Field, TranslatorInterface};

/**
 * Allows use of a callback function to create an error decorator
 *
 * @author GBrabyn
 */
class CallbackDecorator extends ErrorAbstract
{
    /**
     *
     * @var Callable
     */
    private $callable;
    
    /**
     * 
     * @param Callable $callable - function(Element\ElementAbstract $element, Field $field, string[] $errorMessages[, TranslatorInterface $translator]){}
     * @param TranslatorInterface $translator
     */
    public function __construct(Callable $callable, $translator=null)
    {
        $this->callable = $callable;
        
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
        $callable = $this->callable;

        if($this->hasTranslator()){
            return $callable($this->element, $this->field, $this->getErrorMessages(), $this->getTranslator());
        }
        
        return $callable($this->element, $this->field, $this->getErrorMessages());
    }
}
