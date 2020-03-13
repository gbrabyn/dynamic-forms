<?php
namespace GBrabyn\DynamicForms\Manager;

use GBrabyn\DynamicForms\FieldValidator\LaminasValidatorWrapper;
use GBrabyn\DynamicForms\GroupValidator;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Field;
use Laminas\Validator;

/**
 *
 * @author GBrabyn
 */
trait LaminasValidatorTraits
{
    /**
     * 
     * @param int $min
     * @param int $max
     * @param array $options
     * @param Error|null $error
     * @return LaminasValidatorWrapper
     */
    public function stringLength($min, $max, array $options=[], Error $error=null)
    {
        $validator = new Validator\StringLength($options);
        $validator->setMin($min);
        $validator->setMax($max);
        
        return new LaminasValidatorWrapper($validator, $error);
    }
    
    /**
     * 
     * @param string $format
     * @param array $options
     * @param Error $error
     * @return LaminasValidatorWrapper
     */
    public function date($format='Y-m-d', array $options=[], Error $error=null)
    {
        $validator = new Validator\Date($options);
        $validator->setFormat($format);
        
        return new LaminasValidatorWrapper($validator, $error);
    }

    /**
     * Validates time in 24 hour format HH:mm., e.g. 23:59
     *
     * @param Error|null $error
     * @return LaminasValidatorWrapper
     */
    public function time(Error $error=null)
    {
        $error = $error ?: new Error('Not a valid time in format HH:mm, e.g. "23:59"', 'inputInvalidTime', []);

        return $this->date('H:i', [], $error);
    }
    
    /**
     * 
     * @param mixed $min - float, integer or string
     * @param mixed $max - float, integer or string
     * @param bool $inclusive
     * @param array $options
     * @param Error $error
     * @return LaminasValidatorWrapper
     */
    public function between($min, $max, $inclusive=true, array $options=[], Error $error=null)
    {
        $options['min'] = $min;
        $options['max'] = $max;
        $options['inclusive'] = (bool)$inclusive;
        
        $validator = new Validator\Between($options);
        
        return new LaminasValidatorWrapper($validator, $error);
    }
    
    /**
     * 
     * @param mixed $max - maximum value
     * @param bool $inclusive - whether to do inclusive comparisons, allowing equivalence to max
     * @param Error $error
     * @return LaminasValidatorWrapper
     */
    public function lessThan($max, bool $inclusive, Error $error=null)
    {
        $options = ['max'=>$max, 'inclusive'=>$inclusive];
        $validator = new Validator\LessThan($options);
        
        return new LaminasValidatorWrapper($validator, $error);
    }
    
    /**
     * 
     * @param mixed $min
     * @param bool $inclusive - whether to do inclusive comparisons, allowing equivalence to min
     * @param Error $error
     * @return LaminasValidatorWrapper
     */
    public function greaterThan($min, bool $inclusive, Error $error=null)
    {
        $options = ['min'=>$min, 'inclusive'=>$inclusive];
        $validator = new Validator\GreaterThan($options);
        
        return new LaminasValidatorWrapper($validator, $error);
    }
    
    /**
     * 
     * @param array $options
     * @param Error $error
     * @return LaminasValidatorWrapper
     */
    public function email(array $options=[], Error $error=null)
    {
        $validator = new Validator\EmailAddress($options);
        
        return new LaminasValidatorWrapper($validator, $error);
    }
    
    /**
     * 
     * @param array $options
     * @param Error $error
     * @return LaminasValidatorWrapper
     */
    public function uri(array $options=[], Error $error=null)
    {
        if(!\array_key_exists('allowRelative', $options)){
            $options['allowRelative'] = false;
        }

        $validator = new Validator\Uri($options);
        
        return new LaminasValidatorWrapper($validator, $error);
    }
    
    /**
     * 
     * @param string|\Traversable $pattern
     * @param Error $error
     * @return LaminasValidatorWrapper
     */
    public function regex($pattern, Error $error=null)
    {
        $validator = new Validator\Regex($pattern);
        
        return new LaminasValidatorWrapper($validator, $error);
    }
    
    /**
     * Cross-Site Request Forgery - requires a hidden input field be set in the form with the value of $csrfValidator->getHash()
     * 
     * @param Field $field - field that contains the hidden token
     * @param \Laminas\Validator\Csrf $csrfValidator - use output from ::getCsrfTokenValidator()
     * @param Error $error
     * @return \GBrabyn\DynamicForms\GroupValidator\AnonymousFunctionValidator
     */
    public function csrf(Field $field, Validator\Csrf $csrfValidator, Error $error=null)
    {
        if($error === null){
            $error = new Error('The form submitted did not originate from the expected site', 'inputCsrfFail');
        }
        
        return new GroupValidator\AnonymousFunctionValidator(function() use($field, $csrfValidator) {
            return empty($field->getValue()) ? false : $csrfValidator->isValid($field->getValue());
        }, $error);
    }
    
    /**
     * Used to generate the form field and the validator for Cross-Site Request Forgery protection.
     * 
     * @param array $options - with keys: name (string), salt (string), session (Laminas\Session\Container) & timeout (integer)
     * @return \Laminas\Validator\Csrf
     */
    public function getCsrfTokenValidator(array $options=[])
    {
        return new \Laminas\Validator\Csrf($options);
    }
    
}
