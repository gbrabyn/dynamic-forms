<?php
namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ElementAbstract;
use GBrabyn\DynamicForms\Element\Options;
use GBrabyn\DynamicForms\Element\Option;
use GBrabyn\DynamicForms\Element\Attributes;
use GBrabyn\DynamicForms\Element\EscaperInterface;

/**
 *
 * @author GBrabyn
 */
class Select extends ElementAbstract 
{
    /**
     *
     * @var Options 
     */
    private $options;
    
    
    private $optGroupOpen = false;
    
    
    public function __construct(EscaperInterface $escaper, Options $options)
    {
        $this->options = $options;
        parent::__construct($escaper);
    }

    
    protected function optionsElements()
    {
        $ret = [];
        $prevGroup = null;
        
        foreach($this->options AS $option){
            /* @var $option Option */
            /* @var $attributes Attributes */

            $attributes = $option->attributes();
            $attributes->setEscaper($this->getEscaper());
            $attributes->add(['value' => $option->value()]);

            if($this->field->isSelected($option->value())){
                $attributes->add(['selected']);
            }
            
            $ret[] = $this->groupTag($prevGroup, $option->group());
            $ret[] = '<option '.$attributes->getAsString().'>'.$this->escapeHtml($option->label()).'</option>';
            
            $prevGroup = $option->group();
        }
        
        if($this->optGroupOpen === true){
            $ret[] = '</optgroup>';
        }
        
        return $ret;
    }
    
    
    private function groupTag($prevGroup, $currGroup)
    {
        $ret = '';
        
        if($prevGroup === $currGroup){
            return $ret;
        }

        if((string)$prevGroup !== '' && $currGroup !== $prevGroup){
            $ret .= '</optgroup>';
            $this->optGroupOpen = false;
        }
        
        if((string)$currGroup !== '' && $currGroup !== $prevGroup){
            $ret .= '<optgroup label="'.$this->escapeAttr($currGroup).'">';
            $this->optGroupOpen = true;
        }
        
        return $ret;
    }

    /**
     * 
     * @return string
     */
    public function getWithoutErrorMessage()
    {
        return '<select name="'.$this->fieldName.'" '.$this->getAttributesString().'>'.\implode('', $this->optionsElements()).'</select>';
    }
    
    
    public function getOptionValues()
    {
        return $this->options->getValues();
    }
    
    
    public function __clone()
    {
        $this->options = clone $this->options;
        parent::__clone();
    }
    
}
