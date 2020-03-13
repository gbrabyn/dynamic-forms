<?php
namespace GBrabyn\DynamicForms\Element;

use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract;
use GBrabyn\DynamicForms\Exception\DynamicFormsException;
use GBrabyn\DynamicForms\Element\EscaperInterface;

/**
 * Description of ElementAbstract
 *
 * @author GBrabyn
 */
abstract class ElementAbstract 
{

    protected $fieldName;
    
    
    protected $field;
    
    
    protected $errorDecorator;
    
    /**
     *
     * @var Attributes
     */
    private $attributes;
    
    /**
     *
     * @var EscaperInterface 
     */
    private $escaper;
    
    
    public function __construct(EscaperInterface $escaper)
    {
        $this->escaper = $escaper;
    }

    
    public function setName($fieldName)
    {
        $this->fieldName = $fieldName;
    }
    
    
    public function setField(Field $field)
    {
        $this->field = $field;
    }
    
    
    public function setErrorDecorator(ErrorAbstract $errorDecorator)
    {
        $this->errorDecorator = $errorDecorator;
    }
    
    
    public function setAttributes(Attributes $attributes)
    {
        $attributes->setEscaper($this->escaper);
        
        $this->attributes = $attributes;
    }
    
    
    public function getAttributesString()
    {
        if(!$this->attributes){
            throw new DynamicFormsException(__METHOD__.' called without setAttributes() being used.');
        }
        
        return $this->attributes->getAsString();
    }
    
    
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    
    protected function getEscaper()
    {
        return $this->escaper;
    }
    
    
    protected function escapeAttr($string)
    {
        if(\is_scalar($string) === false){
            return '';
        }

        return $this->escaper->escapeAttr((string)$string);
    }
    
    
    protected function escapeHtml($string)
    {
        if(\is_scalar($string) === false){
            return '';
        }

        return $this->escaper->escapeHtml((string)$string);
    }
    
    /**
     * Returns the form element
     * 
     * @return string
     */
    abstract public function getWithoutErrorMessage();

    
    public function __toString()
    {
        if($this->field->isValid()){
            return $this->getWithoutErrorMessage();
        }

        if( ($this->errorDecorator instanceof ErrorAbstract) === false ){
            throw new DynamicFormsException(__METHOD__.' called without an error decorator being set');
        }
            
        $this->errorDecorator->setElement($this);  
        $this->errorDecorator->setField($this->field);
  
        return (string)$this->errorDecorator;
    }
    
    
    public function __clone()
    {
        if($this->attributes){
            $this->attributes = clone $this->attributes;
        }
    }
    
}
