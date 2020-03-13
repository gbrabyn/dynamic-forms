<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/6/18
 * Time: 1:22 PM
 */

namespace GBrabyn\DynamicForms\ErrorDecorator;

use GBrabyn\DynamicForms\TranslatorInterface;

class PlaceholderStandAloneDecorator extends StandAloneErrorAbstract
{
    private $placeHolderString;

    /**
     * Allows a simple string to be used as an error decorator. E.g. '<div class="elError">${error}</div>'.
     * ${error} will be replaced with the error message.
     *
     * @param string $placeHolderString - use '${error}' to place error message in decorator markup
     * @param null|TranslatorInterface $translator
     */
    public function __construct(string $placeHolderString, $translator=null)
    {
        $this->placeHolderString = $placeHolderString;

        if($translator){
            $this->setTranslator($translator);
        }
    }


    public function __toString() : string
    {
        $errorMessage = $this->getErrorMsg(0);

        return \str_replace('${error}', $errorMessage, $this->placeHolderString);
    }
}