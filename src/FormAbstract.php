<?php
namespace GBrabyn\DynamicForms;

use GBrabyn\DynamicForms\Exception\DynamicFormsException;
use GBrabyn\DynamicForms\Element\Attributes;

/**
 *
 * @author GBrabyn
 */
abstract class FormAbstract implements \SplSubject
{
    use SplSubjectTrait;

    /**
     *
     * @var bool - stores whether the submitted form validates or not
     */
    private $valid = true;
    
    /**
     *
     * @var Field[]
     */
    private $fields = [];

    /**
     * @var String[] - field keys. Fields that are not to appear when using getDefinedValues()
     */
    private $removeFields = [];
    
    /**
     *
     * @var array - stores form values
     */
    private $formValues = [];
    
    /**
     *
     * @var GroupValidatorInterface/GroupValidatorInterface[] 
     */
    private $validators = [];
    
    /**
     *
     * @var Error[]
     */
    private $errors = [];
    
    /**
     *
     * @var bool
     */
    private $useTransformers = false;
    
    /**
     *
     * @var TransformInterface[] - array of TransformInterface instances used to transform field values from the form before they are given back to the script
     */
    private $univeralTransformers = [];

    
    private $elementDecorators = [];
    
    
    private $errorDecorators = [];
    
    
    private $defaultErrorDecorator;
    
    
    private $standAloneErrorDecorators = [];
    
    
    private $defaultStandAloneErrorDecorator;
    
    
    private $formErrorsDecorator;
    
    /**
     * Gets an FormField object with $fieldKey that belongs to $this form
     *
     * @param string $fieldKey - key which accesses the field. Normally this is the same as the field name attribute but when the field 
     *                           is an array (i.e. name attribute is an array) then use dot as separator for each part of the array name 
     *                           e.g. <input type="text" name="city[district][streets]" /> is $fieldKey of 'city.district.streets'. 
     *                           When numerically indexed array then need the index to get the name, i.e. person[] is 'person.0' or 'person.1', etc.
     * 
     * @return Field
     */
    public function field($fieldKey)
    {
        $key = \rtrim($fieldKey, '.');

        if(!isset($this->fields[$key])){
            $this->createField($key);
        }

        return $this->fields[$key];
    }

    /**
     * Prevents the field appearing in getDefinedValues()
     *
     * @param string $fieldKey
     */
    public function removeField($fieldKey)
    {
        $this->removeFields[] = $fieldKey;
    }
    
    /**
     * @param string ...$fieldKeys
     * @return Field[]
     */
    protected function getFields(string ... $fieldKeys) : array
    {
        return \array_map(function($fieldKey){
            return $this->field($fieldKey);
        }, $fieldKeys);
    }
    
    /**
     * @param Field ...$fields
     * @return bool
     */
    protected function fieldsValid(Field ... $fields)
    {
        foreach($fields AS $field){
            if($field->isValid() === false){
                return false;
            }
        }

        return true;
    }
    
    /**
     * 
     * @param string $fieldKey
     */
    private function createField($fieldKey)
    {
        $this->fields[$fieldKey] = new Field();
        $value = $this->getValueFromArray($fieldKey, $this->formValues);
        $this->fields[$fieldKey]->setValue($value, $this->useTransformers);
    }

    /**
     * 
     * @param string $fieldKey
     * @param array $array
     * @return mixed
     */
    private function getValueFromArray($fieldKey, array $array)
    {
        $keysArray = \explode('.', $fieldKey);

        foreach($keysArray AS $k){
            if(false === \is_array($array) || false === \array_key_exists($k, $array) ){
                return null;
            }
            
            $array = $array[$k];
        }
        
        return $array;
    }
    
    /**
     * Where fields are defined and any field or form-wide transformers and validators added.
     * 
     * If a field has no transformers or validators then it need not be added in this method 
     * although it may be a good security practice to define all fields here and only use 
     * getDefinedValues() to bring form values into the rest of your script.
     * 
     * This method gets called after populate() so $formValues can be used to
     * define and add validators for any JavaScript dynamically added fields.
     * 
     * @return null
     */
    abstract protected function config(array $formValues);

    /**
     * Populates the FormField objects with values when there is one in $fieldVals
     * 
     * @param array $fieldVals
     * @param bool $useTransformers
     * @return \GBrabyn\DynamicForms\FormAbstract
     */
    public function populate(array $fieldVals, $useTransformers)
    {
        $this->notify('populateStart');
        $this->useTransformers = $useTransformers;
        $this->config($fieldVals);
        $this->formValues = $fieldVals;

        if($useTransformers === true){
            /* $this->univeralTransformers would get set in $this->config() hence transforming here */
            \array_walk_recursive($this->formValues, function(&$value){
                $value = $this->_getUniversalTransformed($value);
            });
        }
        
        foreach($this->formValues AS $k => $v){
            $this->_assignValsToFieldsIfExists($k, $v);
        }

        $this->notify('populateEnd');

        return $this;
    }
    
    
    private function _assignValsToFieldsIfExists($key, $value)
    {
        if( isset($this->fields[$key]) ){
            $this->fields[$key]->setValue($value, $this->useTransformers);
        }
            
        if( \is_array($value) ){
            $this->_arrayGetValues($key, $value);
        }
    }

    /**
     * Used when populating form with multi-dimensional array. It maps the keys in the array to a dot dilimited string format for the fields name, 
     * i.e. 'aaa.bbb.ccc' represents an array $aaa['bbb']['ccc']
     * 
     * @param string $key - first key in the associative array
     * @param mixed $values - the value
     */
    private function _arrayGetValues($key, $values)
    {
        foreach($values AS $k => $v){
            $tKey = $key.'.'.$k;
            $this->_assignValsToFieldsIfExists($tKey, $v);
        }
    }
    
    
    private function _getEndNodeFieldKeys()
    {
        $keys = \array_keys($this->fields);
        \usort($keys, function($a, $b){
            return \strlen($a) - \strlen($b);   // shortest to longest
        });
        
        $endNodeKeys = [];
        
        foreach($keys AS $k){
            $this->_removeBranchKeys($endNodeKeys, $k);
            $endNodeKeys[] = $k;
        }

        // Preserve the order of the keys
        $ret = [];
        foreach(\array_keys($this->fields) AS $key){
            if(\in_array($key, $endNodeKeys)){
                $ret[] = $key;
            }
        }

        return $ret;
    }
    
    
    private function _removeBranchKeys(array &$keyList, $key)
    {
        $keyParts = \explode('.', $key);
        $searchParts = [];

        foreach($keyParts AS $part){
            $searchParts[] = $part;
            $searchKey = \implode('.', $searchParts);

            $position = \array_search($searchKey, $keyList);

            if($position !== false){
                unset($keyList[$position]);
            }
        }
    }
    
    
    public function getDefinedValues()
    {
        $this->notify('getDefinedValues');
        $ret = [];
        foreach($this->_getEndNodeFieldKeys() AS $fieldKey){
            if(false === \in_array($fieldKey, $this->removeFields)){
                $ret = $this->_makeFieldKeyEntryInArray($ret, $fieldKey);
            }
        }
        
        return $ret;
    }
    
    
    private function _makeFieldKeyEntryInArray(array $array, $fieldKey)
    {
        $kPath = \explode('.', $fieldKey);
        $rPath = \array_reverse($kPath);
        
        $first = \array_shift($rPath);
        $value = $this->field($fieldKey)->getValue();
        $ret = [$first => $value];

        foreach($rPath AS $key){
            $ret = [$key => $ret];
        }

        return \array_replace_recursive($array, $ret);
    }
    
    
    public function getValues()
    {
        return \array_replace_recursive($this->formValues, $this->getDefinedValues());
    }

    /**
     *
     * @return bool - whether the submitted form validates correctly (true) or not (false)
     */
    public function validate()
    {
        $this->notify('validateStart');

        foreach($this->fields AS $field){
            if($field->validate() === false){
                $this->valid = false;
            }
        }
        
        foreach($this->validators AS $validator){
            if($validator->isValid() === false){
                $this->valid = false;
                $this->addError($validator->getError());
            }
        }

        $this->notify('validateEnd');

        return $this->valid;
    }

    /**
     * 
     * @param \GBrabyn\DynamicForms\GroupValidator\GroupValidatorInterface $validator
     * @return \GBrabyn\DynamicForms\FormAbstract
     */
    public function addValidator(GroupValidator\GroupValidatorInterface $validator)
    {
        $this->validators[] = $validator;
        
        return $this;
    }

    /**
     * Add a custom error message to the form
     *
     * @param $error
     * @return $this
     * @throws DynamicFormsException
     */
    public function addError($error)
    {
        if($error === null){
            return $this;
        }elseif( !($error instanceof Error) ){
            throw new DynamicFormsException(__METHOD__.' called with with $error not instance of Error: '.\gettype($error));
        }

        if(!\in_array($error, $this->errors)){
            $this->errors[] = $error;
        }

        $this->valid = false;
        
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
     * @return array - fieldKey => Error[]
     */
    public function getAllFieldErrors()
    {
        $errors = [];

        foreach($this->fields AS $fieldKey => $field){
            if($field->isValid() === false){
                $errors[$fieldKey] = $field->getErrors();
            }
        }
        
        return $errors;
    }
    
    /**
     * 
     * @param \GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract $errorDecorator
     * @return \GBrabyn\DynamicForms\Form
     */
    public function setDefaultErrorDecorator(ErrorDecorator\ErrorAbstract $errorDecorator)
    {
        $this->defaultErrorDecorator = $errorDecorator;
        
        return $this;
    }
    
    /**
     * 
     * @param \GBrabyn\DynamicForms\ErrorDecorator\StandAloneErrorAbstract $errorDecorator
     * @return \GBrabyn\DynamicForms\Form
     */
    public function setDefaultStandAloneErrorDecorator(ErrorDecorator\StandAloneErrorAbstract $errorDecorator)
    {
        $this->defaultStandAloneErrorDecorator = $errorDecorator;
        
        return $this;
    }
    
    /**
     * Sets the decorator to display "form has errors messages" and any general form wide error messages
     * 
     * @param \GBrabyn\DynamicForms\ErrorDecorator\StandAloneErrorAbstract $decorator
     * @return $this
     */
    public function setFormErrorsDecorator(ErrorDecorator\StandAloneErrorAbstract $decorator)
    {
        $this->formErrorsDecorator = $decorator;
        
        return $this;
    }
    
    /**
     * 
     * @param array|Transform\TransformAbstract $transformers - Transform\TransformAbstract instances
     * @return \GBrabyn\DynamicForms\Form
     */
    public function addUniveralTransformers($transformers)
    {
        if(\is_array($transformers)){
            foreach($transformers AS $transformer){
                $this->_addUniveralTransformer($transformer);
            }
        }else{
            $this->_addUniveralTransformer($transformers);
        }

        return $this;
    }
    
    /**
     * 
     * @param \GBrabyn\DynamicForms\TransformAbstract $transformer
     */
    private function _addUniveralTransformer(Transform\TransformAbstract $transformer)
    {
        $this->univeralTransformers[] = $transformer;
    }
    
    /**
     * 
     * @param mixed $value
     * @return type
     */
    private function _getUniversalTransformed($value)
    {
        if(!\is_scalar($value)){
            return $value;
        }

        foreach($this->univeralTransformers AS $transformer){
            $transformer->setValue($value);
            $value = $transformer->getValue();
        }

        return $value;
    }
    
    /**
     * 
     * @param string $key
     * @param \GBrabyn\DynamicForms\ElementAbstract $decorator
     */
    public function registerElement($key, Element\ElementAbstract $decorator)
    {
        $this->elementDecorators[$key] = $decorator;
    }
    
    /**
     * 
     * @param string $key
     * @param \GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract $decorator
     */
    public function registerErrorDecorator($key, ErrorDecorator\ErrorAbstract $decorator)
    {
        $this->errorDecorators[$key] = $decorator;
    }
    
    /**
     * 
     * @param string $key
     * @param \GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract $decorator
     */
    public function registerStandAloneErrorDecorator($key, ErrorDecorator\StandAloneErrorAbstract $decorator)
    {
        $this->standAloneErrorDecorators[$key] = $decorator;
    }

    /**
     * 
     * @param \GBrabyn\DynamicForms\ElementAbstract|string $elementDecorator
     * @param string $fieldName
     * @param string $fieldKey
     * @param array $attributes
     * @param ErrorDecorator\ErrorAbstract|string|null $errorDecorator
     * @return string
     */
    public function element($elementDecorator, $fieldName, $fieldKey, array $attributes=[], $errorDecorator=null)
    {
        $element = $this->getElementDecorator($elementDecorator);
        $element->setName($fieldName);
        $element->setField($this->field($fieldKey));
        
        $elAttributes = new Attributes($attributes);
        $element->setAttributes($elAttributes);

        $errorDec = $this->getErrorDecorator($errorDecorator);

        if($errorDec){
            $element->setErrorDecorator($errorDec);
        }

        return (string)$element;
    }
    
    /**
     * 
     * @param \GBrabyn\DynamicForms\ElementAbstract|string $elementDecorator - when a string then the key used in registerElement($key, $decorator)
     * @return \GBrabyn\DynamicForms\ElementAbstract
     * @throws DynamicFormsException
     */
    private function getElementDecorator($elementDecorator)
    {
        if($elementDecorator instanceof Element\ElementAbstract){
            return $elementDecorator;
        }

        if(false === \is_string($elementDecorator)){
            throw new DynamicFormsException(__METHOD__ .'() called with $elementDecorator having an invalid data type of: '.\gettype($elementDecorator));
        }
            
        if(!isset($this->elementDecorators[$elementDecorator]) ){
            throw new DynamicFormsException(__METHOD__ .'() called with a string $elementDecorator "'.$elementDecorator.'" that is not registered in elementDecorators: '.\print_r($this->elementDecorators, true));
        }
        
        return clone $this->elementDecorators[$elementDecorator];
    }
    
    /**
     * 
     * @param \GBrabyn\DynamicForms\ErrorAbstract|string|null $errorDecorator - when a string then the key used in registerErrorDecorator($key, $decorator)
     * @return \GBrabyn\DynamicForms\ErrorAbstract
     * @throws DynamicFormsException
     */
    private function getErrorDecorator($errorDecorator)
    {
        if($errorDecorator instanceof ErrorDecorator\ErrorAbstract){
            return $errorDecorator;
        }elseif($errorDecorator === null){
            return $this->defaultErrorDecorator;
        }
        
        if(false === \is_string($errorDecorator)){
            throw new DynamicFormsException(__METHOD__ .'() called with $errorDecorator having an invalid data type of: '.\gettype($errorDecorator));
        }elseif(!isset($this->errorDecorators[$errorDecorator]) ){
            throw new DynamicFormsException(__METHOD__ .'() called with a string $errorDecorator "'.$errorDecorator.'" that is not registered in errorDecorators: '.\print_r($this->errorDecorators, true));
        }
        
        return $this->errorDecorators[$errorDecorator];
    }

    /**
     * 
     * @param \GBrabyn\DynamicForms\StandAloneErrorAbstract|string $errorDecorator - when a string then the key used in registerStandAloneErrorDecorator($key, $decorator)
     * @return \GBrabyn\DynamicForms\ErrorDecorator\StandAloneErrorAbstract
     * @throws DynamicFormsException
     */
    private function getStandAloneErrorDecorator($errorDecorator)
    {
        if($errorDecorator instanceof ErrorDecorator\StandAloneErrorAbstract){
            return $errorDecorator;
        }elseif($errorDecorator === null){
            return clone $this->defaultStandAloneErrorDecorator;
        }
        
        if(false === \is_string($errorDecorator)){
            throw new DynamicFormsException(__METHOD__ .'() called with $errorDecorator having an invalid data type of: '.\gettype($errorDecorator));
        }elseif(!isset($this->standAloneErrorDecorators[$errorDecorator]) ){
            throw new DynamicFormsException(__METHOD__ .'() called with a string $errorDecorator "'.$errorDecorator.'" that is not registered in errorDecorators: '.\print_r($this->errorDecorators, true));
        }
        
        return clone $this->standAloneErrorDecorators[$errorDecorator];
    }

    /**
     * 
     * @param string $fieldKey
     * @param string|null|\GBrabyn\DynamicForms\StandAloneErrorAbstract $decorator
     * @return string
     */
    public function fieldError($fieldKey, $decorator=null)
    {
        $errors = $this->field($fieldKey)->getErrors();
        
        if(\count($errors) === 0){
            return '';
        }
        
        $errorDec = $this->getStandAloneErrorDecorator($decorator);
        $errorDec->setErrors($errors);
        
        return (string)$errorDec;
    }

    /**
     * Displays a message if any errors in form and displays any form-wide general error messages
     * 
     * @return string
     * @throws DynamicFormsException
     */
    public function errors()
    {
        if($this->valid === true){
            return '';
        }
        
        if(!$this->formErrorsDecorator){
            throw new DynamicFormsException(__METHOD__.' called without setFormErrorsDecorator() being first called');
        }     
        
        $this->formErrorsDecorator->setErrors($this->getErrors());
        
        return (string)$this->formErrorsDecorator;
    }

    /**
     * Iterate over a group of fields with related sequential fieldKeys
     *
     * @param array $formValues - values populating the form
     * @param string ...$parentKeys - the parent of 'aa.bb.0' is 'aa.bb'. $parentKey must represent a sequential array.
     * @return iterable - each iteration contains Field[]. Each Field array is ordered same as in $parentKeys
     */
    public function seqKeyFields(array $formValues, string ... $parentKeys)
    {
        $parentValues = [];
        foreach($parentKeys AS $parentKey){
            $this->field($parentKey)->setValue([], false); // empty array is expected if no populated values are set
            $parentValues[$parentKey] = $this->getValueFromArray($parentKey, $formValues);
        }

        foreach($parentValues AS $parentKey => $values){
            if(false === \is_array($values)){
                return [];
            }
        }

        $x = 0;
        $firstParent = $parentKeys[0];

        foreach(\array_keys($parentValues[$firstParent]) AS $key){
            if($key !== $x++){
                return;   // for security reasons keeping getDefinedValues() with predictable values
            }

            $fieldKeys = \array_map(function($fieldKey) use($key) {
                return $fieldKey.'.'.$key;
            }, $parentKeys);

            yield $key => $this->getFields(...$fieldKeys);
        }
    }

    /**
     * Positive integer keys belonging to a field value
     *
     * @param array $formValues - values populating the form
     * @param string $parentKey - the parent of 'aa.bb.0' is 'aa.bb'.
     * @return array
     */
    public function intKeys(array $formValues, string $parentKey) : array
    {
        $values = $this->getValueFromArray($parentKey, $formValues);

        if(false === \is_array($values)){
            return [];
        }

        return \array_filter(\array_keys($values), function($key){
            return \filter_var($key, \FILTER_VALIDATE_INT)!==false && \abs($key)==$key;
        });
    }

    /**
     * @param array $formValues - values populating the form
     * @param string $parentKey - the parent key of 'aa.bb.0.cc' is 'aa.bb'
     * @param string ...$childSuffixes - the child suffix of 'aa.bb.0.cc' is 'cc'
     * @return \Generator
     */
    public function series(array $formValues, string $parentKey, string ...$childSuffixes)
    {
        $this->field($parentKey)->setValue([], false);
        $keys = $this->intKeys($formValues, $parentKey);

        foreach($keys AS $key){
            $fieldKeys = \array_map(function($childSuffix) use($parentKey, $key) {
                return $parentKey.'.'.$key.'.'.$childSuffix;
            }, $childSuffixes);

            yield $key => $this->getFields(...$fieldKeys);
        }
    }

    
}
