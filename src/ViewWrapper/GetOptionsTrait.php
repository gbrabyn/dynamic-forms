<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/5/18
 * Time: 3:46 PM
 */

namespace GBrabyn\DynamicForms\ViewWrapper;

use GBrabyn\DynamicForms\Element\Options;

trait GetOptionsTrait
{
    public function getOptions(string $propertyName) : Options
    {
        if(\property_exists($this, $propertyName) && $this->$propertyName instanceof Options){
            return clone $this->$propertyName;
        }

        throw new \InvalidArgumentException('No property with name "'.$propertyName.'" exists in $form that holds '.Options::class);
    }
}