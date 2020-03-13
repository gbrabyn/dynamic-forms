<?php
namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ElementAbstract;
use GBrabyn\DynamicForms\Element\EscaperInterface;

/**
 * Produces an HTML <input> box of type="hidden" with the value pre-set.
 *
 * @author GBrabyn
 */
class Hidden extends ElementAbstract 
{
    /**
     *
     * @var mixed
     */
    private $value;
    
    /**
     * 
     * @param EscaperInterface $escaper
     * @param mixed $value
     */
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

        $parts = [
            'type="hidden"',
            'name="'.$this->fieldName.'"',
        ];

        if(false === $this->getValueFromAttributes()){
            $parts[] = 'value="'.$this->escapeAttr($this->value).'"';
        }
        
        if($this->getAttributes()){
            $parts[] = $this->getAttributesString();
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
