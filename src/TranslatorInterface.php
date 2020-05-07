<?php
namespace GBrabyn\DynamicForms;

/**
 *
 * @author GBrabyn
 */
interface TranslatorInterface
{
    
    /**
     * 
     * @param string $translationKey 
     * @return string
     */
    public function translate($translationKey);
    
}
