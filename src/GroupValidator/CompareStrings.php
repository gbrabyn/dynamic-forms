<?php
namespace GBrabyn\DynamicForms\GroupValidator;

/**
 * Used to compare string values of 2 fields. I.e. are they the same, different, is subject greater than, less than compareWith, etc.
 *
 * @author GBrabyn
 */
class CompareStrings extends CompareNumbers
{

    protected function validate()
    {
        if(\in_array($this->subject->getValue(), [null,''], true) || \in_array($this->compareWith->getValue(), [null,''], true)){
            return;
        }

        if(false === $this->compare($this->subject->getValue(), $this->compareWith->getValue(), $this->type)){
            $this->valid = false;
            $this->subject->addError( $this->error );
        }
        
        $this->validateCalled = true;
    }
    
    /**
     * 
     * @param string $subjectValue
     * @param string $compareWithValue
     * @param string $type
     * @return bool
     */
    private function compare($subjectValue, $compareWithValue, $type)
    {
        $comp = \strcmp($subjectValue, $compareWithValue);

        if($comp === null){
            return;
        }elseif($comp == 0){
            if(\in_array($type, ['>', '<', '<>'])){
                return false;
            }
        }elseif($comp < 0){
            if(\in_array($type, ['>', '>=', '=='])){
                return false;
            }
        }elseif($comp > 0){
            if(\in_array($type, ['<', '<=', '=='])){
                return false;
            } 
        }

        return true;
    }
}
