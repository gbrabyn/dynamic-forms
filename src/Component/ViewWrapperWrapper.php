<?php


namespace GBrabyn\DynamicForms\Component;


use GBrabyn\DynamicForms\Manager\ElementManagerAbstract;
use GBrabyn\DynamicForms\ViewWrapper\ViewWrapperAbstract;

class ViewWrapperWrapper
{
    /** @var ComponentAbstract */
    private $component;

    /** @var ViewWrapperAbstract */
    private $viewWrapper;

    /** @var ElementManagerAbstract */
    private $elements;

    /** @var ?string */
    private $parentKey;

    public function __construct(ViewWrapperAbstract $viewWrapper, ComponentAbstract $component, ElementManagerAbstract $elementManager, ?string $parentKey)
    {
        $this->viewWrapper = $viewWrapper;
        $this->component = $component;
        $this->elements = $elementManager;
        $this->parentKey = $parentKey;
    }

    public function __call($name, $arguments)
    {
        if(\method_exists($this->viewWrapper, $name)){
            return $this->viewWrapper->$name(...$this->amendArguments($name, $arguments));
        }

        throw new \BadMethodCallException('Method '.$name.' does not exist');
    }

    private function amendArguments(string $methodName, array $arguments) : array
    {
        if($this->parentKey === null){
            return $arguments;
        }

        $args = $this->getArgNamesAndValues($methodName, $arguments);

        if(\array_key_exists('parentKey', $args) && \is_string($args['parentKey']) == true){
            $args['parentKey'] = \implode('.', \array_filter([$this->parentKey, $args['parentKey']]));
        }elseif(\array_key_exists('fieldKey', $args) && \is_string($args['fieldKey']) == true){
            $args['fieldKey'] = \implode('.', \array_filter([$this->parentKey, $args['fieldKey']]));
        }

        if(\array_key_exists('attributes', $args) && \is_array($args['attributes'])){
            $args['attributes'] = $this->amendAttributes($args['attributes']);
        }

        return \array_values($args);
    }

    private function amendAttributes(array $attributes) : array
    {
        if(isset($attributes['name'])){
            $attributes['name'] = $this->addParentToNameAttribute($this->parentKey, $attributes['name']);
        }

        return $attributes;
    }

    private function addParentToNameAttribute(?string $parentKey, string $nameAttribute) : string
    {
        if(! $parentKey){
            return $nameAttribute;
        }

        $parent = $this->generateName($parentKey);

        if(\strpos($nameAttribute, '[') === false){
            $child = '['.$nameAttribute.']';
        }else{
            $child = '['.\preg_replace('/\[/', '][', $nameAttribute, 1);
        }

        return $parent.$child;
    }

    /**
     * @param string $fieldKey
     * @return string
     */
    private function generateName(string $fieldKey) : string
    {
        $key = \preg_replace('/[^a-zA-Z0-9-_\.]/', '', $fieldKey);
        $parts = \explode('.', $key);

        if(\count($parts) === 1){
            return $key;
        }

        return \array_shift($parts).'['.\implode('][', $parts).']';
    }

    private function getArgNamesAndValues(string $methodName, array $arguments) : array
    {
        $m = new \ReflectionMethod($this->viewWrapper, $methodName);
        $ret = [];
        foreach($m->getParameters() as $k => $param){
            $ret[$param->getName()] = $arguments[$k] ?? $param->getDefaultValue();
        }

        return $ret;
    }

    public function component(string $propertyName, ?string $parentkey) : self
    {
        $component = $this->component->getComponent($propertyName);
        $key = \implode('.', \array_filter([$this->parentKey, $parentkey]));

        return new self($this->viewWrapper, $component, $this->elements, $key);
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
        if(\substr($fieldKey, -1) !== '.'){
            $fieldKey .= '.';
        }

        $fieldKey = \implode('.', \array_filter([$this->parentKey, $fieldKey]));
        $attributes = $this->amendAttributes($attributes);
        $options = $this->component->getOptions($optionsKey);
        $element = $this->elements->checkboxList($options);

        return $this->viewWrapper->customElement($element, $fieldKey, $attributes, $errorDecorator);
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

        $fieldKey = \implode('.', \array_filter([$this->parentKey, $fieldKey]));
        $attributes = $this->amendAttributes($attributes);
        $options = $this->component->getOptions($optionsKey);
        $element = $this->elements->radioList($options);

        return $this->viewWrapper->customElement($element, $fieldKey, $attributes, $errorDecorator);
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
        $fieldKey = \implode('.', \array_filter([$this->parentKey, $fieldKey]));
        $attributes = $this->amendAttributes($attributes);
        $options = $this->component->getOptions($optionsKey);
        $element = $this->elements->select($options);

        return $this->viewWrapper->customElement($element, $fieldKey, $attributes, $errorDecorator);
    }
}