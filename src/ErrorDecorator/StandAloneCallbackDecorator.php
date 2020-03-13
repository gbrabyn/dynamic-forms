<?php
namespace GBrabyn\DynamicForms\ErrorDecorator;

use GBrabyn\DynamicForms\TranslatorInterface;

/**
 * Allows use of a callback function to create a Stand Alone Error Decorator
 *
 * @author GBrabyn
 */
class StandAloneCallbackDecorator extends StandAloneErrorAbstract
{
    /**
     *
     * @var Callable
     */
    private $callable;
    
    /**
     * 
     * @param Callable $callable - function(string[] $errorMessages[, TranslatorInterface $translator])
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
        $callable = $this->callable;

        if($this->hasTranslator()){
            return $callable($this->getErrorMessages(), $this->getTranslator());
        }
        
        return $callable($this->getErrorMessages());
    }
}
