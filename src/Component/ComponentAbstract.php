<?php


namespace GBrabyn\DynamicForms\Component;

use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\GroupValidator\GroupValidatorInterface;
use GBrabyn\DynamicForms\Manager\TransformManager;
use GBrabyn\DynamicForms\Manager\ValidatorManager;
use GBrabyn\DynamicForms\ViewWrapper\GetOptionsTrait;

abstract class ComponentAbstract implements ComponentUserInterface
{
    use ComponentUserTrait, GetOptionsTrait;

    /** @var ComponentUserInterface */
    protected $parent;

    private $parentKey;

    /* @var ValidatorManager */
    protected $validators;

    /* @var TransformManager */
    protected $transformers;

    /**
     * Form or ComponentAbstract instance using this component
     */
    public function setParent(ComponentUserInterface $parent)
    {
        $this->parent = $parent;
    }

    public function setValidatorManager(ValidatorManager $validators)
    {
        $this->validators = $validators;
    }

    public function setTransformManager(TransformManager $transformers)
    {
        $this->transformers = $transformers;
    }

    abstract protected function activate();

    public function doActivate()
    {
        $this->activate();
        $this->activateComponents();
    }

    abstract protected function config(array $formValues, $options=[]);

    public function configure(?string $parentKey, array $formValues, $options=[])
    {
        $this->parentKey = $parentKey;
        $this->config($formValues, $options);
        $this->parentKey = null;
    }

    protected function fieldKey(string $childKey) : string
    {
        if($this->parentKey){
            return $this->parentKey.'.'.$childKey;
        }

        return $childKey;
    }

    protected function isset(string $fieldKey, array $array) : bool
    {
        $keysArray = \explode('.', $this->fieldKey($fieldKey));

        foreach($keysArray AS $k){
            if(false === \is_array($array) || false === \array_key_exists($k, $array) ){
                return false;
            }

            $array = $array[$k];
        }

        return true;
    }

    /**
     *
     * @param string $fieldKey
     * @param array $array
     * @return mixed
     */
    protected function getFromArray(string $fieldKey, array $array)
    {
        $keysArray = \explode('.', $this->fieldKey($fieldKey));

        foreach($keysArray AS $k){
            if(false === \is_array($array) || false === \array_key_exists($k, $array) ){
                return null;
            }

            $array = $array[$k];
        }

        return $array;
    }

    protected function field(string $key) : Field
    {
        return $this->parent->field($this->fieldKey($key));
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

    protected function addValidator(GroupValidatorInterface $validator)
    {
        $this->parent->addValidator($validator);
    }

    protected function addError($error)
    {
        $this->parent->addError($error);
    }

    /**
     * @param array $formValues - values populating the form
     * @param string $parentKey - the parent key of 'aa.bb.0.cc' is 'aa.bb'
     * @param string ...$childSuffixes - the child suffix of 'aa.bb.0.cc' is 'cc'
     * @return \Generator
     */
    protected function series(array $formValues, string $parentKey, string ...$childSuffixes)
    {
        return $this->parent->series($formValues, $this->fieldKey($parentKey), ...$childSuffixes);
    }

    /**
     * Positive integer keys belonging to a field value
     *
     * @param array $formValues - values populating the form
     * @param string $parentKey - the parent of 'aa.bb.0' is 'aa.bb'.
     * @return array
     */
    protected function intKeys(array $formValues, string $parentKey) : array
    {
        return $this->parent->intKeys($formValues, $this->fieldKey($parentKey));
    }
}