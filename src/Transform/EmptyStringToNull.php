<?php
namespace GBrabyn\DynamicForms\Transform;

/**
 *
 * @author GBrabyn
 */
class EmptyStringToNull extends TransformAbstract
{
    /**
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->value === '' ? null : $this->value;
    }
}
