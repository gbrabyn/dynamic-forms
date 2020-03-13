<?php
namespace GBrabyn\DynamicForms\ErrorDecorator;

use GBrabyn\DynamicForms\Element\ElementAbstract;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Exception\DynamicFormsException;
use \GBrabyn\DynamicForms\TranslatorTrait;
use \GBrabyn\DynamicForms\MessageTrait;

/**
 *
 * @author GBrabyn
 */
abstract class ErrorAbstract extends BaseErrorAbstract
{
    use TranslatorTrait, MessageTrait;
    

    protected $element;
    
    
    protected $field;

    /**
     * 
     * @param ElementAbstract $element
     */
    public function setElement(ElementAbstract $element)
    {
        $this->element = $element;
    }

    
    public function setField(Field $field)
    {
        $this->field = $field;
    }

    /**
     * 
     * @param int|null $max
     * @return string[]
     */
    protected function getErrorMessages($max=null)
    {
        return $this->messages($this->field->getErrors(), $max);
    }
    
    /**
     * 
     * @param int $index
     * @return string
     */
    protected function getErrorMsg($index)
    {
        return $this->getMessage($this->field->getErrors(), $index);
    }
    
    /**
     * 
     * @throws \DynamicFormsException
     */
    protected function exceptionsCheck()
    {
        if($this->field === null){
            throw new DynamicFormsException(\get_class($this).' called without setField() being used', 1);
        }elseif($this->element === null){
            throw new DynamicFormsException(\get_class($this).' called without setElement() being used', 2);
        }elseif($this->field->isValid() !== false){
            throw new DynamicFormsException(\get_class($this).' called on a field with valid input', 3);
        }elseif(\count( $this->field->getErrors() ) === 0){
            throw new DynamicFormsException(\get_class($this).': no errors set', 4);
        }
    }
    
}
