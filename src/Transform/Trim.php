<?php
namespace GBrabyn\DynamicForms\Transform;

/**
 * Trims white-space & line-breaks from ends of string
 *
 * @author GBrabyn
 */
class Trim extends TransformAbstract
{
    /**
     * 
     * @return mixed
     */
    public function getValue()
    {
        if(\is_scalar($this->value) === false){
            return '';
        }
        
        return \trim($this->value);
    }
}
