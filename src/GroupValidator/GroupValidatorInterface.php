<?php
namespace GBrabyn\DynamicForms\GroupValidator;

/**
 *
 * @author GBrabyn
 */
interface GroupValidatorInterface 
{
    /**
     * 
     * @return bool
     */
    public function isValid();

    
    /**
     * 
     * @return Error|null - when the error messages are added to fields then it may be OK to return null
     */
    function getError();
    
}
