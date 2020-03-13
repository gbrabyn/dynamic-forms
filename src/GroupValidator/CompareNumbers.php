<?php
namespace GBrabyn\DynamicForms\GroupValidator;

use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Field;

/**
 * Used to compare numeric values of 2 fields. I.e. are they the same, different, is subject greater than, less than compareWith, etc.
 * It is expected that $subject and $compareWith fields will be validated for integer or float values before this CompareNumbers is called.
 *
 * @author GBrabyn
 */
class CompareNumbers implements GroupValidatorInterface
{
    /**
     *
     * @var Error 
     */
    protected $error;
    
    /**
     *
     * @var bool
     */
    protected $valid = true;
    
    /**
     *
     * @var bool
     */
    protected $validateCalled = false;
    
    /**
     *
     * @var Field 
     */
    protected $subject;
    
    /**
     *
     * @var Field 
     */
    protected $compareWith;
    
    /**
     *
     * @var string
     */
    protected $type;
    
    /**
     * 
     * @param Field $subject
     * @param Field $compareWith
     * @param string $comparisionType
     * @param Error $error
     */
    public function __construct(Field $subject, Field $compareWith, $comparisionType,  Error $error)
    {
        $this->subject = $subject;
        $this->compareWith = $compareWith;
        $this->validateComparisonType($comparisionType);
        $this->type = $comparisionType;
        $this->error = $error;
    }
    
    /**
     * 
     * @param string $comparisionType
     * @throws \InvalidArgumentException
     */
    private function validateComparisonType($comparisionType)
    {
        switch($comparisionType){
            case '>':
            case '<':
            case '<=':
            case '>=':
            case '==':
            case '<>':
                break;
            default:
                throw new \InvalidArgumentException('Using invalid $comparisionType of: '.$comparisionType);
        }
    }
    
    
    protected function validate()
    {
        if(!\is_numeric($this->subject->getValue()) || !\is_numeric($this->compareWith->getValue())){
            return;
        }

        if(! $this->compare($this->subject->getValue(), $this->compareWith->getValue(), $this->type) ){
            $this->valid = false;
            $this->subject->addError( $this->error );
        }
        
        $this->validateCalled = true;
    }
    
    /**
     * 
     * @param int|float $subjectValue
     * @param int|float $compareWithValue
     * @param string $type
     * @return bool
     */
    private function compare($subjectValue, $compareWithValue, $type)
    {
        switch($type){
            case '>':
                return $subjectValue > $compareWithValue;
            case '<':
                return $subjectValue < $compareWithValue;
            case '<=':
                return $subjectValue <= $compareWithValue;
            case '>=':
                return $subjectValue >= $compareWithValue;
            case '==':
                return $subjectValue == $compareWithValue;
            case '<>':
                return $subjectValue <> $compareWithValue;
        }
    }

    /**
     * 
     * @return bool
     */
    public function isValid()
    {
        if($this->validateCalled === false){
            $this->validate();
        }
        
        return $this->valid;
    }

    /**
     * 
     * @return Error|null
     */
    public function getError()
    {
        return null;
    }

}
