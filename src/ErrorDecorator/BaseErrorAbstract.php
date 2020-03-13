<?php
namespace GBrabyn\DynamicForms\ErrorDecorator;

/**
 * Description of BaseErrorAbstract
 *
 * @author GBrabyn
 */
abstract class BaseErrorAbstract 
{
    /**
     * 
     * @param int|null $max
     * @return string[]
     */
    abstract protected function getErrorMessages($max=null);
    
    /**
     * 
     * @param int $index - index of the message in the message array
     * @return string
     */
    abstract protected function getErrorMsg($index);
    
    /**
     * 
     * @return string
     */
    abstract public function __toString();
    
}
