<?php
namespace GBrabyn\DynamicForms\GroupValidator\WhenThen;

use GBrabyn\DynamicForms\Field;

/**
 *
 * @author GBrabyn
 */
interface WhenInterface 
{
    /**
     * @return bool
     */
    public function conditionApplies();

}
