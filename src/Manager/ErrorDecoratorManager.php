<?php
namespace GBrabyn\DynamicForms\Manager;

use GBrabyn\DynamicForms\TranslatorInterface;
use GBrabyn\DynamicForms\ErrorDecorator;

/**
 *
 * @author GBrabyn
 */
class ErrorDecoratorManager
{
    protected $doctype;
    protected $allowedDocTypes = ['html5'];
    protected $translator;
    
    /**
     * 
     * @param string $doctype
     * @param TranslatorInterface $translator
     */
    public function __construct($doctype, TranslatorInterface $translator=null)
    {
        $this->setDocType($doctype);
        $this->translator = $translator;
    }

    /**
     * 
     * @param string $doctype
     * @throws \InvalidArgumentException
     */
    protected function setDocType($doctype)
    {
        $lDocType = \strtolower($doctype);
        
        if(!\in_array($lDocType, $this->allowedDocTypes)){
            throw new \InvalidArgumentException('Invalid value for $doctype: '.$lDocType.'. Allowed '
                    . 'doctypes = '.\print_r($this->allowedDocTypes, true).'. You may need to create your '
                    . 'own elements to meet your doctype needs.');
        }
        
        $this->doctype = $lDocType;
    }
    
    /**
     * Displays any field error message above the element
     */
    public function above()
    {
        $class = '\GBrabyn\DynamicForms\ErrorDecorator\\'.$this->doctype.'\Above';
        
        return new $class($this->translator);
    }

    /**
     * Displays any field error message below the element
     */
    public function below()
    {
        $class = '\GBrabyn\DynamicForms\ErrorDecorator\\'.$this->doctype.'\Below';
        
        return new $class($this->translator);
    }
    
    /**
     * Uses a callback function to produce the error decorator
     * 
     * @param Callable $callable - function(Element\ElementAbstract $element, Field $field, string[] $errorMessages[, TranslatorInterface $translator]){}
     * @return \GBrabyn\DynamicForms\ErrorDecorator\CallbackDecorator
     */
    public function callback(Callable $callable)
    {
        return new ErrorDecorator\CallbackDecorator($callable, $this->translator);
    }
    
    /**
     * Used to show if errors in form and any set of form general messages
     */
    public function form()
    {
        $class = '\GBrabyn\DynamicForms\ErrorDecorator\\'.$this->doctype.'\Form';
        
        return new $class($this->translator);
    }
    
    /**
     * Used when it is not wanted for error messages to display along side the element. 
     * If field has an error then the element will be highlighted however.
     */
    public function none()
    {
        $class = '\GBrabyn\DynamicForms\ErrorDecorator\\'.$this->doctype.'\None';
        
        return new $class();
    }

    /**
     * Allows a simple string to be used as an error decorator. E.g. '<div class="elError">${error}<br>${element}</div>'.
     * ${error} will be replaced with the error message. ${element} will be replaced with the form field elem
     *
     * @param string $markupWithPlaceholders - use '${error}' & '${element}' to place error message and form element in decorator markup
     * @return \GBrabyn\DynamicForms\ErrorDecorator\PlaceholderFieldDecorator
     */
    public function placeholder(string $markupWithPlaceholders)
    {
        return new ErrorDecorator\PlaceholderFieldDecorator($markupWithPlaceholders, $this->translator);
    }

    /**
     * Allows a simple string to be used as an error decorator. E.g. '<div class="elError">${error}</div>'.
     * ${error} will be replaced with the error message.
     *
     * @param string $markupWithPlaceholder - use '${error}' to place error message in decorator markup
     * @return ErrorDecorator\PlaceholderStandAloneDecorator
     */
    public function placeholderStandAlone(string $markupWithPlaceholder)
    {
        return new ErrorDecorator\PlaceholderStandAloneDecorator($markupWithPlaceholder, $this->translator);
    }
    
    /**
     * Displays any field error message to the right side of the element
     */
    public function rightSide()
    {
        $class = '\GBrabyn\DynamicForms\ErrorDecorator\\'.$this->doctype.'\RightSide';
        
        return new $class($this->translator);
    }
    
    /**
     * Shows a field error message separately from the element
     */
    public function separate()
    {
        $class = '\GBrabyn\DynamicForms\ErrorDecorator\\'.$this->doctype.'\Separate';
        
        return new $class($this->translator);
    }
    
    /**
     * Shows a field error message separately from the form element and without any decorating HTML
     */
    public function separateRaw()
    {
        $class = '\GBrabyn\DynamicForms\ErrorDecorator\SeparateRaw';
        
        return new $class($this->translator);
    }
    
    /**
     * Uses a callback function to decorate a field error message separately from the element
     * 
     * @param Callable $callable - function(string[] $errorMessages[, TranslatorInterface $translator])
     * @return \GBrabyn\DynamicForms\ErrorDecorator\StandAloneCallbackDecorator
     */
    public function standAloneCallback(Callable $callable)
    {
        return new \GBrabyn\DynamicForms\ErrorDecorator\StandAloneCallbackDecorator($callable, $this->translator);
    }
    
}
