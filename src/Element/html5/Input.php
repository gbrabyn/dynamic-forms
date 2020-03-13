<?php
namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ElementAbstract;
use GBrabyn\DynamicForms\Element\EscaperInterface;

/**
 * Produces an HTML <input> box
 *
 * @author GBrabyn
 */
class Input extends ElementAbstract
{
    /**
     *
     * @var string
     */
    protected $type;
    
    /**
     * 
     * @param EscaperInterface $escaper
     * @param string $type
     */
    public function __construct(EscaperInterface $escaper, $type)
    {
        $this->type = $type;
        parent::__construct($escaper);
    }
    
    /**
     * 
     * @return string
     */
    public function getWithoutErrorMessage()
    {
        return '<input type="'.$this->escapeAttr($this->type).'" name="'.$this->fieldName.'" '
                . 'value="'.$this->escapeAttr( $this->field->getValue() ).'" '
                . $this->getAttributesString()
                . '>';
    }
}
