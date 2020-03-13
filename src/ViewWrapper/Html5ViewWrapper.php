<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/5/18
 * Time: 3:51 PM
 */

namespace GBrabyn\DynamicForms\ViewWrapper;

use GBrabyn\DynamicForms\Element\Attributes;
use GBrabyn\DynamicForms\ErrorDecorator\ErrorAbstract;


class Html5ViewWrapper extends ViewWrapperAbstract
{
    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorAbstract $errorDecorator
     * @return string
     */
    public function color(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('color');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    public function datalist(string $optionsKey, array $attributes) : string
    {
        $options = $this->form->getOptions($optionsKey);
        $element = $this->elements->datalist($options);

        $elAttributes = new Attributes($attributes);
        $element->setAttributes($elAttributes);

        return (string)$element;
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorAbstract $errorDecorator
     * @return string
     */
    public function date(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('date');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorAbstract $errorDecorator
     * @return string
     */
    public function email(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('email');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorAbstract $errorDecorator
     * @return string
     */
    public function number(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('number');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorAbstract $errorDecorator
     * @return string
     */
    public function range(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('range');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorAbstract $errorDecorator
     * @return string
     */
    public function search(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('search');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorAbstract $errorDecorator
     * @return string
     */
    public function time(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('time');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

    /**
     * @param string $fieldKey
     * @param array $attributes
     * @param null|string|ErrorAbstract $errorDecorator
     * @return string
     */
    public function url(string $fieldKey, array $attributes=[], $errorDecorator=null) : string
    {
        $element = $this->elements->input('url');
        $fieldName = $this->getName($fieldKey, $attributes);

        return $this->form->element($element, $fieldName, $fieldKey, $attributes, $errorDecorator);
    }

}