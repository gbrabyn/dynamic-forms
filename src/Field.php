<?php
namespace GBrabyn\DynamicForms;

/**
 * Behaviour and data of a form field
 *
 * @author GBrabyn
 */
class Field implements \SplSubject
{
    use SplSubjectTrait;
    
    /**
     *
     * @var string - the value the field holds
     */
    private $value = '';
    
    /**
     *
     * @var array - store of validation functions to be used 
     */
    private $validators = array();
    
    /**
     *
     * @var array - TransformInterface instances used to transform field values from form before they are given back to the script
     */
    private $transformers = array();
    
    /**
     *
     * @var array - Error instances
     */
    private $errors = array();
    
    /**
     *
     * @var bool - whether the field has a valid value or not
     */
    private $validValue = true;

    /**
     * 
     * @param mixed $value
     * @param bool $useTransformers
     * @return \GBrabyn\DynamicForms\Field
     */
    public function setValue($value, $useTransformers)
    {
        $this->value = $useTransformers 
                            ? $this->_getTransformed($value) 
                            : $value;
        
        $this->valueSet = true;
        
        return $this;
    }
    
    /**
     * 
     * @param mixed $value
     * @return type
     */
    private function _getTransformed($value)
    {
        foreach($this->transformers AS $transformer){
            $transformer->setValue($value);
            $value = $transformer->getValue();
        }
        
        return $value;
    }
    
    
    public function getValue()
    {
        return $this->value;
    }
    
    
    /**
     * Validates the field value
     *
     * @return boolean
     */
    public function validate()
    {
        if($this->validValue === false){
            return false;
        }

        foreach($this->validators AS $validator){
            if(     ($this->value === null || $this->value === '')
                &&  $validator->useWhenEmpty() === false
            ){
                continue;
            }

            $validator->setValue($this->value);
            
            if($validator->isValid() === false){
                $this->addError($validator->getError());
                break;
            }
        }
        
        $this->notify('validated');

        return $this->validValue;
    }
    
    /**
     * Whether the field holds a valid value
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->validValue;
    }

    /**
     *
     * @param Error $error - manual way of adding error to field on validation
     * @return Field
     */
    public function addError(Error $error)
    {
        if(!\in_array($error, $this->errors)){
            $this->errors[] = $error;
            $this->notify('errorAdded');
        }
        
        $this->validValue = false;
        
        return $this;
    }
    
    /**
     * 
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $validators - array of strings representing the validator method names (methods belong to this class)
     * @return Field
     */
    public function addValidators($validators)
    {
        if(\is_array($validators)){
            foreach($validators AS $validator){
                $this->_addValidator($validator);
            }
        }else{
            $this->_addValidator($validators);
        }

        return $this;
    }
    
    
    private function _addValidator(FieldValidator\FieldValidatorAbstract $validator)
    {
        $this->validators[] = $validator;
    }
    
    /**
     * 
     * @param array|Transform\TransformAbstract $transformers
     * @return \GBrabyn\DynamicForms\Field
     */
    public function addTransformers($transformers)
    {
        if(\is_array($transformers)){
            foreach($transformers AS $transformer){
                $this->_addTransformer($transformer);
            }
        }else{
            $this->_addTransformer($transformers);
        }

        return $this;
    }
    
    
    private function _addTransformer(Transform\TransformAbstract $transformer)
    {
        $this->transformers[] = $transformer;
    }

    
    public function isSelected($value)
    {
        if(\is_array($this->value)){
            if(\in_array($value, $this->value)){
                return true;
            }
        }elseif( (string)$value === (string)$this->value && ($this->value !== false && $value !== '') ){
            return true;
        }elseif($value == 'true' && $this->value === true){
            return true;
        }elseif($value == 'false' && $this->value === false){
            return true;
        }
        
        return false;
    }

}
