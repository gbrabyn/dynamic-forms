<?php
namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ElementAbstract;
use GBrabyn\DynamicForms\Element\EscaperInterface;

/**
 * Produces a input box with number in localised format
 *
 * @author GBrabyn
 */
class InputNumber extends ElementAbstract
{
    protected $locale;
    
    public function __construct(EscaperInterface $escaper, $locale)
    {
        $this->locale = $locale;
        parent::__construct($escaper);
    }
    
    
    protected function getFormattedValue()
    {
        $fmt = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL );
        
        if( !\is_numeric( $this->field->getValue() ) ){
            return $this->field->getValue();
        }
        
        return $fmt->format( $this->field->getValue() );
    }
    
    /**
     * 
     * @return string
     */
    public function getWithoutErrorMessage()
    {
        return '<input type="number" name="'.$this->fieldName.'" '
                . 'value="'.$this->escapeAttr( $this->getFormattedValue() ).'" '
                . $this->getAttributesString()
                . '>';
    }
}
