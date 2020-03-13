<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/5/18
 * Time: 9:52 AM
 */

namespace GBrabyn\DynamicForms\ViewWrapper;

use GBrabyn\DynamicForms;
use GBrabyn\DynamicForms\{FormAbstract, Field};
use GBrabyn\DynamicForms\ErrorDecorator;
use GBrabyn\DynamicForms\Manager\{ElementManagerAbstract, ErrorDecoratorManager};
use GBrabyn\DynamicForms\Exception\DynamicFormsException;


abstract class ViewWrapperAbstract
{
    /* @var FormAbstract */
    protected $form;

    /* @var ElementManagerAbstract */
    protected $elements;

    /* @var ErrorDecoratorManager */
    protected $errorDecorators;


    public function __construct(FormAbstract $form, ElementManagerAbstract $elements, ErrorDecoratorManager $errorDecorators)
    {
        if(! $form instanceof ViewWrapperUserInterface){
            throw new \InvalidArgumentException('Argument $form must implement '.ViewWrapperUserInterface::class);
        }

        $this->form = $form;
        $this->elements = $elements;
        $this->errorDecorators = $errorDecorators;
    }

    /**
     *
     * @param DynamicForms\Element\ElementAbstract | string $elementDecorator
     * @param string $fieldKey
     * @param array $attributes
     * @param string|null $errorDecorator
     * @return string
     */
    public function customElement($elementDecorator, string $fieldKey, array $attributes=[], ?string $errorDecorator=null) : string
    {
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($elementDecorator, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @return string
     */
    protected function generateName(string $fieldKey) : string
    {
        $key = \preg_replace('/[^a-zA-Z0-9-_\.]/', '', $fieldKey);
        $parts = \explode('.', $key);

        if(\count($parts) === 1){
            return $key;
        }

        return \array_shift($parts).'['.\implode('][', $parts).']';
    }


    protected function getName(string $fieldKey, array &$attributes) : string
    {
        $fieldName = $attributes['name'] ?? $this->generateName($fieldKey);
        unset($attributes['name']);

        return $fieldName;
    }


    protected function getArrayName(string $fieldKey, array &$attributes) : string
    {
        if(\substr($fieldKey, -1) !== '.'){
            $fieldKey .= '.';
        }

        $fieldName = $attributes['name'] ?? $this->generateName($fieldKey);
        unset($attributes['name']);

        return $fieldName;
    }


    public function field(string $fieldKey) : Field
    {
        return $this->form->field($fieldKey);
    }

    public function component(string $propertyName, ?string $parentKey=null) : DynamicForms\Component\ViewWrapperWrapper
    {
        $component = $this->form->getComponent($propertyName);

        return new DynamicForms\Component\ViewWrapperWrapper($this, $component, $this->elements, $parentKey);
    }

    public function errorDecorators() : ErrorDecoratorManager
    {
        return $this->errorDecorators;
    }

    /**
     * Alias to errorDecorators()
     */
    public function errDec() : ErrorDecoratorManager
    {
        return $this->errorDecorators();
    }

    /**
     *
     * @return array - fieldKey => Error[]
     */
    public function getAllFieldErrors() : array
    {
        return $this->form->getAllFieldErrors();
    }

    /**
     * @param ErrorDecorator\ErrorAbstract $errorDecorator
     * @return $this
     */
    public function setDefaultErrorDecorator(ErrorDecorator\ErrorAbstract $errorDecorator)
    {
        $this->form->setDefaultErrorDecorator($errorDecorator);

        return $this;
    }

    /**
     * @param ErrorDecorator\StandAloneErrorAbstract $errorDecorator
     * @return $this
     */
    public function setDefaultStandAloneErrorDecorator(ErrorDecorator\StandAloneErrorAbstract $errorDecorator)
    {
        $this->form->setDefaultStandAloneErrorDecorator($errorDecorator);

        return $this;
    }

    /**
     * Sets the decorator to display "form has errors messages" and any general form wide error messages
     *
     * @param ErrorDecorator\StandAloneErrorAbstract $decorator
     * @return $this
     */
    public function setFormErrorsDecorator(ErrorDecorator\StandAloneErrorAbstract $decorator)
    {
        $this->form->setFormErrorsDecorator($decorator);

        return $this;
    }

    /**
     *
     * @param string $fieldKey
     * @param string | null | ErrorDecorator\StandAloneErrorAbstract $decorator
     * @return string
     */
    public function fieldError($fieldKey, $decorator=null) : string
    {
        return $this->form->fieldError($fieldKey, $decorator);
    }

    /**
     * Displays a message if any errors in form and displays any form-wide general error messages
     *
     * @return string
     * @throws DynamicFormsException
     */
    public function errors() : string
    {
        return $this->form->errors();
    }

    /**
     * Gives the next key to create for an array. Purpose: place in a data attribute for use by JavaScript so as to
     * create a new row of fields in a list.
     *
     * @param string $parentKey
     * @return int
     */
    public function nextKey(string $parentKey) : int
    {
        $parentValue = $this->form->field($parentKey)->getValue();

        if($parentValue === null){
            return 0;
        }

        if(false === \is_array($parentValue)){
            throw new \InvalidArgumentException($parentKey.' does not represent an array value. Value = '.$parentValue);
        }

        $intKeys = \array_filter(\array_keys($parentValue), function($key){
            return \filter_var($key, \FILTER_VALIDATE_INT)!==false && \abs($key)==$key;
        }, \ARRAY_FILTER_USE_KEY);

        if(\count($intKeys) === 0){
            return 0;
        }

        return \max($intKeys) + 1;
    }

    /**
     * Positive integer keys belonging to a field value
     *
     * @param string $parentKey
     * @return array
     */
    public function intKeys(string $parentKey) : array
    {
        $values = \is_array($this->field($parentKey)->getValue()) ? $this->field($parentKey)->getValue() : [];

        return \array_filter(\array_keys($values), function($key){
            return \filter_var($key, \FILTER_VALIDATE_INT)!==false && \abs($key)==$key;
        });
    }

    /**
     * @param string $fieldKey
     * @param mixed $value
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function checkbox(string $fieldKey, $value, array $attributes=[], $errorDecorator=null)
    {
        $element = $this->elements->checkbox($value);
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $optionsKey
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function checkboxList(string $optionsKey, string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $options = $this->form->getOptions($optionsKey);
        $element = $this->elements->checkboxList($options);
        $fieldName = $this->getArrayName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function text(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('text');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function hidden(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        if(\array_key_exists('value', $attributes)){
            $element = $this->elements->hidden(null);
        }else{
            $element = $this->elements->input('hidden');
        }

        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function password(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('password');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function file(string $fieldKey, array $attributes=[], $errorDecorator=null)
    {
        $element = $this->elements->file();
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param mixed $value
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function radio(string $fieldKey, $value, array $attributes=[], $errorDecorator=null)
    {
        $element = $this->elements->radio($value);
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $optionsKey
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function radioList(string $optionsKey, string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $options = $this->form->getOptions($optionsKey);
        $element = $this->elements->radioList($options);
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     *
     * @param string $optionsKey - name of property in FormAbstract instance $form holding the Options
     * @param string $fieldKey
     * @param array $attributes
     * @param string|null|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function select(string $optionsKey, string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $options = $this->form->getOptions($optionsKey);
        $element = $this->elements->select($options);
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorDecorator\ErrorAbstract $errorDecorator
     * @return string
     */
    public function textarea(string $fieldKey, array $attributes=[], $errorDecorator=null)
    {
        $element = $this->elements->textarea();
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }


}