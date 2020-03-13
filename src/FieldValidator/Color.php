<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/7/18
 * Time: 5:25 PM
 */

namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

class Color extends FieldValidatorAbstract
{
    /**
     *
     * @var Error
     */
    private $error;


    public function __construct(Error $error=null)
    {
        $this->error = $error;
    }

    /**
     *
     * @return bool
     */
    public function isValid()
    {
        if(\strlen($this->value) !== 7){
            return false;
        }

        if(\substr($this->value, 0, 1) !== '#'){
            return false;
        }

        $hex = \substr($this->value, 1, 6);

        return \ctype_xdigit($hex);
    }

    /**
     *
     * @return Error
     */
    public function getError()
    {
        if($this->error){
            return $this->error;
        }

        return new Error('Color must be in hexidecimal format "#ffffff"', 'inputInvalidHexColor', []);
    }
}