<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/9/18
 * Time: 3:03 PM
 */

namespace GBrabyn\DynamicForms\Transform;


class EnsureArray extends TransformAbstract
{
    /**
     *
     * @return array
     */
    public function getValue()
    {
        if(false === \is_array($this->value)){
            return [];
        }

        return $this->value;
    }
}