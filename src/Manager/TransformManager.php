<?php
namespace GBrabyn\DynamicForms\Manager;

/**
 *
 * @author GBrabyn
 */
class TransformManager 
{
    protected $locale = 'en_US';

    /**
     * 
     * @return \GBrabyn\DynamicForms\Transform\EmptyStringToNull
     */
    public function emptyStringToNull()
    {
        return new \GBrabyn\DynamicForms\Transform\EmptyStringToNull();
    }

    /**
     * @return \GBrabyn\DynamicForms\Transform\EnsureArray
     */
    public function ensureArray()
    {
        return new \GBrabyn\DynamicForms\Transform\EnsureArray();
    }

    /**
     * 
     * @return \GBrabyn\DynamicForms\Transform\RemoveNumberLocalisation
     */
    public function removeNumberLocalisation($locale=null)
    {
        $_local = $locale ?: $this->locale;
        
        return new \GBrabyn\DynamicForms\Transform\RemoveNumberLocalisation($_local);
    }
    
    /**
     * 
     * @return \GBrabyn\DynamicForms\Transform\Trim
     */
    public function trim()
    {
        return new \GBrabyn\DynamicForms\Transform\Trim();
    }
    
    
}
