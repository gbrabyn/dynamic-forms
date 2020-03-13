<?php
namespace GBrabyn\DynamicForms\GroupValidator\WhenThen;

use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;

/**
 *
 */
interface ThenInterface 
{
    /**
     * @return bool
     */
    public function meetConditions();

}
