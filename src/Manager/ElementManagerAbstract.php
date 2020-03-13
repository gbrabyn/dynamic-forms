<?php
namespace GBrabyn\DynamicForms\Manager;

use GBrabyn\DynamicForms\Element\Options;

/**
 *
 * @author GBrabyn
 */
abstract class ElementManagerAbstract 
{
    protected $doctype;
    protected $allowedDocTypes = ['html5'];
    protected $locale = 'en_US';
    protected $escaper;
    
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
    
    
    abstract protected function setEscaper($escaper);
    
    /**
     * 
     * @param Callable $callable
     * @return \GBrabyn\DynamicForms\Element\ElementAbstract
     */
    public function callback(Callable $callable)
    {
        return new \GBrabyn\DynamicForms\Element\Callback($callable, $this->escaper);
    }
    
    /**
     * 
     * @param scalar|null $value
     * @return \GBrabyn\DynamicForms\Element\ElementAbstract
     */
    public function checkbox($value)
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\Checkbox';
        
        return new $class($this->escaper, $value);
    }

    public function checkboxList(Options $options)
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\CheckboxList';
        
        return new $class($this->escaper, $options);
    }

    public function datalist(Options $options)
    {
        $class = '\GBrabyn\DynamicForms\Element\html5\DataList';

        return new $class($this->escaper, $options);
    }
    
    public function file()
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\File';
        
        return new $class($this->escaper);
    }
    
    public function hidden($value)
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\Hidden';
        
        return new $class($this->escaper, $value);
    }
    
    public function input(string $type)
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\Input';
        
        return new $class($this->escaper, $type);
    }

    public function inputNumber($locale=null)
    {
        $_local = $locale ?: $this->locale;
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\InputNumber';
        
        return new $class($this->escaper, $_local);
    }
    
    public function radio($value)
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\Radio';
        
        return new $class($this->escaper, $value);
    }
    
    public function radioList(Options $options)
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\RadioList';
        
        return new $class($this->escaper, $options);
    }

    public function select(Options $options)
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\Select';
        
        return new $class($this->escaper, $options);
    }
    
    public function textarea()
    {
        $class = '\GBrabyn\DynamicForms\Element\\'.$this->doctype.'\Textarea';
        
        return new $class($this->escaper);
    }
    
}
