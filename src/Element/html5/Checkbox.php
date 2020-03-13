<?php
namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ElementAbstract;
use GBrabyn\DynamicForms\Element\EscaperInterface;

/**
 * Produces an HTML <input type="checkbox"> box
 *
 * @author GBrabyn
 */
class Checkbox extends ElementAbstract 
{
    
    protected $value;
    
    
    public function __construct(EscaperInterface $escaper, $value)
    {
        $this->value = $value;
        parent::__construct($escaper);
    }
    
    /**
     * 
     * @return string
     */
    public function getWithoutErrorMessage()
    {   
        $this->checkValueNotDuplicated();
        
        $value = $this->getValueFromAttributes() ? $this->getAttributes()->get('value') : $this->value;
        $parts = [
            'type="checkbox"',
            'name="'.$this->fieldName.'"',
        ];

        if(false === $this->getValueFromAttributes()){
            $parts[] = 'value="'.$this->escapeAttr($this->value).'"';
        }
        
        if($this->getAttributes() && $this->getAttributesString()){
            $parts[] = $this->getAttributesString();
        }
        
        if($this->field->isSelected($value)){
            $parts[] = 'checked';
        }

        return '<input '.\implode(' ', $parts).'>';
    }
    
    /**
     * 
     * @throws \InvalidArgumentException
     */
    private function checkValueNotDuplicated()
    {
        if($this->value !== null && $this->getAttributes() !== null && $this->getAttributes()->has('value') == true){
            throw new \InvalidArgumentException('Cannot have value assigned to both Checkbox and its attributes');
        }
    }
    
    /**
     * 
     * @return boolean
     */
    private function getValueFromAttributes()
    {
        if($this->value !== null){
            return false;
        }
        
        return ($this->getAttributes() !== null && $this->getAttributes()->hasValue('value') == true);
    }
    
}
