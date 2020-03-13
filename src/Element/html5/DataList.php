<?php


namespace GBrabyn\DynamicForms\Element\html5;

use GBrabyn\DynamicForms\Element\ElementAbstract;
use GBrabyn\DynamicForms\Element\Options;
use GBrabyn\DynamicForms\Element\Option;
use GBrabyn\DynamicForms\Element\Attributes;
use GBrabyn\DynamicForms\Element\EscaperInterface;

class DataList extends ElementAbstract
{
    /** @var Options */
    private $options;

    public function __construct(EscaperInterface $escaper, Options $options)
    {
        $this->options = $options;
        parent::__construct($escaper);
    }

    protected function optionsElements()
    {
        $ret = [];

        foreach($this->options AS $option){
            /* @var $option Option */
            /* @var $attributes Attributes */

            $attributes = $option->attributes();
            $attributes->setEscaper($this->getEscaper());
            $attributes->add(['value' => $option->value()]);

            $ret[] = '<option '.$attributes->getAsString().'>'.$this->escapeHtml($option->label()).'</option>';
        }

        return $ret;
    }

    /**
     * @return string
     * @throws \GBrabyn\DynamicForms\Exception\DynamicFormsException
     */
    public function getWithoutErrorMessage()
    {
        return '<datalist '.$this->getAttributesString().'>'.\implode('', $this->optionsElements()).'</datalist>';
    }

    public function __toString()
    {
        if($this->getAttributes()->hasValue('id') === false){
            throw new \InvalidArgumentException('Datalist element must have an ID attribute');
        }

        return $this->getWithoutErrorMessage();
    }

    public function getOptionValues()
    {
        return $this->options->getValues();
    }
}