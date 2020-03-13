<?php
namespace GBrabyn\DynamicForms\ErrorDecorator;

use \GBrabyn\DynamicForms\TranslatorTrait;
use \GBrabyn\DynamicForms\MessageTrait;

/**
 * For showing a field error message in a separate place to the field element
 *
 * @author GBrabyn
 */
abstract class StandAloneErrorAbstract extends BaseErrorAbstract 
{
    use TranslatorTrait, MessageTrait;
    
    
    protected $errors = [];

    /**
     * @param  \GBrabyn\DynamicForms\Error[] $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * 
     * @param int|null $max
     * @return string[]
     */
    protected function getErrorMessages($max=null)
    {
        return $this->messages($this->errors, $max);
    }
    
    /**
     * 
     * @param int $index
     * @return string
     */
    protected function getErrorMsg($index)
    {
        return $this->getMessage($this->errors, $index);
    }
}
