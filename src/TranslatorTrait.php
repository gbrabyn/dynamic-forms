<?php
namespace GBrabyn\DynamicForms;

use GBrabyn\DynamicForms\TranslatorInterface;
use GBrabyn\DynamicForms\Exception\DynamicFormsException;

/**
 *
 * @author GBrabyn
 */
trait TranslatorTrait 
{

    private $translator;
    
    
    final protected function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    /**
     * 
     * @return bool
     */
    protected function hasTranslator()
    {
        return $this->translator instanceof TranslatorInterface;
    }

    /**
     * 
     * @return TranslatorInterface
     * @throws DynamicFormsException
     */
    final protected function getTranslator()
    {
        if($this->translator === null){
            throw new DynamicFormsException(__METHOD__.' called without a translator being set. Use setTranslator().');
        }

        return $this->translator;
    }
    
    /**
     * 
     * @param string $translationKey
     * @return string
     */
    protected function translate($translationKey)
    {
        return $this->getTranslator()->translate($translationKey);
    }
}
