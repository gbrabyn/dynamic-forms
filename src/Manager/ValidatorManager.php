<?php
namespace GBrabyn\DynamicForms\Manager;

use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\FieldValidator;
use GBrabyn\DynamicForms\GroupValidator;
use GBrabyn\DynamicForms\GroupValidator\WhenThen;

/**
 *
 * @author GBrabyn
 */
class ValidatorManager 
{
    /**
     * Validates a field contains a value in the allowed list
     * 
     * @param array $allowed
     * @param Error|null $error
     * @return Allowed
     */
    public function allowed(array $allowed, Error $error=null)
    {
        return new FieldValidator\Allowed($allowed, $error);
    }
    
    /**
     * Validates that an array field contains an array value 
     * and its items are in the allowed list
     * 
     * @param array $allowed
     * @param Error|null $error
     * @return AllowedMultiple
     */
    public function allowedMultiple(array $allowed, Error $error=null)
    {
        return new FieldValidator\AllowedMultiple($allowed, $error);
    }

    /**
     * Validates the value is null or an array. Also validates number of items in array meets requirements of $min & $max
     *
     * @param int $min
     * @param int|null $max
     * @param Error|null $error
     * @return FieldValidator\ArrayCount
     */
    public function arrayCount(int $min=0, ?int $max=null, Error $error=null)
    {
        return new FieldValidator\ArrayCount($min, $max, $error);
    }

    /**
     * Validates a hexidecimal color string, e.g. #fff000
     *
     * @param Error|null $error
     * @return FieldValidator\Color
     */
    public function color(Error $error=null)
    {
        return new FieldValidator\Color($error);
    }
    
    /**
     * Makes field entry compulsory
     * 
     * @param Error $error
     * @return Required
     */
    public function required(Error $error=null)
    {
        return new FieldValidator\Required($error);
    }
    
    /**
     * 
     * @param Error $error
     * @return Integer
     */
    public function integer(Error $error=null)
    {
        return new FieldValidator\Integer($error);
    }
    
    /**
     * 
     * @param Error $error
     * @return PositiveInteger
     */
    public function posInt(Error $error=null)
    {
        return new FieldValidator\PositiveInteger($error);
    }
    
    /**
     * 
     * @param Error $error
     * @return Float
     */
    public function float(Error $error=null)
    {
        return new FieldValidator\FloatingPointInteger($error);
    }
    
    /**
     * Validates value is an integer, float or string, i.e. prevents hackers making an array value
     * 
     * @param Error $error
     * @return \GBrabyn\DynamicForms\FieldValidator\Scalar
     */
    public function scalar(Error $error=null)
    {
        return new FieldValidator\Scalar($error);
    }
    
    /**
     * Validates a value is an array with sequential keys - protection from hackers tampering with values
     * 
     * @param Error $error
     * @return \GBrabyn\DynamicForms\FieldValidator\SequentialArray
     */
    public function sequentialArray(Error $error=null)
    {
        return new FieldValidator\SequentialArray($error);
    }
    
    /**
     * Used to compare numeric values of 2 fields. I.e. are they the same, different, is subject greater than, less than compareWith, etc.
     * It is expected that $subject and $compareWith fields will be validated for integer or float values before this CompareNumbers is called.
     * 
     * @param Field $subject
     * @param Field $compareWith
     * @param string $comparisionType - '>', '>=', '<', '<=', '==' or '<>'
     * @param Error $error
     * @return CompareNumbers
     */
    public function compareNumbers(Field $subject, Field $compareWith, $comparisionType, Error $error)
    {
        return new GroupValidator\CompareNumbers($subject, $compareWith, $comparisionType, $error);
    }
    
    /**
     * Used to compare string values of 2 fields. I.e. are they the same, different, is subject greater than, less than compareWith, etc.
     * 
     * @param Field $subject
     * @param Field $compareWith
     * @param string $comparisionType - '>', '>=', '<', '<=', '==' or '<>'
     * @param Error $error
     * @return CompareStrings
     */
    public function compareStrings(Field $subject, Field $compareWith, $comparisionType, Error $error)
    {
        return new GroupValidator\CompareStrings($subject, $compareWith, $comparisionType, $error);
    }
    
    /**
     * Used to validate when one field in $fields is filled in then all other fields in $fields must also be filled in. 
     * I.e. either none or all the fields must be filled in to validate
     * 
     * @param Field[] $fields
     * @param Error $error
     * @return MutuallyRequired
     */
    public function mutuallyRequired(array $fields, Error $error=null)
    {
        return new GroupValidator\MutuallyRequired($fields, $error);
    }
    
    /**
     * If the $when condition applies then $then must meet its conditions in order to validate.
     * See \GBrabyn\DynamicForms\GroupValidator\WhenThen for some pre-made classes of WhenInterface & ThenInterface.
     * Can be used to make a field entry required when another field (or group of fields) are empty for instance
     * or only allow certain inputs in a field according to what is selected in another field.
     * 
     * @param \GBrabyn\DynamicForms\GroupValidator\WhenThen\WhenInterface $when
     * @param \GBrabyn\DynamicForms\GroupValidator\WhenThen\ThenInterface $then
     * @param Error $formError
     * @return WhenThen
     */
    public function whenThen(WhenThen\WhenInterface $when, WhenThen\ThenInterface $then, Error $formError=null)
    {
        return new GroupValidator\WhenThen($when, $then, $formError);
    }
    
    /**
     * Used to validate that all Fields have unique values
     * 
     * @param array $fields
     * @param Error $error
     * @return \GBrabyn\DynamicForms\GroupValidator\Unique
     */
    public function unique(array $fields, Error $error=null)
    {
        return new GroupValidator\Unique($fields, $error);
    }
}
