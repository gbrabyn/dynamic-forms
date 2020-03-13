<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/6/18
 * Time: 12:49 PM
 */

namespace GBrabyn\DynamicForms\ErrorDecorator;

use GBrabyn\DynamicForms\TranslatorInterface;

class PlaceholderFieldDecorator extends ErrorAbstract
{
    private $placeHolderString;

    /**
     * Allows a simple string to be used as an error decorator. E.g. '<div class="elError">${error}<br>${element}</div>'.
     * ${error} will be replaced with the error message. ${element} will be replaced with the form field element.
     *
     * @param string $placeHolderString - use '${error}' & '${element}' to place error message and form element in decorator markup
     * @param null|TranslatorInterface $translator
     */
    public function __construct($placeHolderString, $translator=null)
    {
        $this->placeHolderString = $placeHolderString;

        if($translator){
            $this->setTranslator($translator);
        }
    }


    public function __toString()
    {
        $this->exceptionsCheck();
        $errorMessage = $this->getErrorMsg(0);
        $element = $this->element->getWithoutErrorMessage();

        return \str_replace(['${error}', '${element}'], [$errorMessage, $element], $this->placeHolderString);
    }
}