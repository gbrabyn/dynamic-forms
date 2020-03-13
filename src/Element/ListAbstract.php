<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/29/18
 * Time: 11:36 AM
 */

namespace GBrabyn\DynamicForms\Element;


abstract class ListAbstract extends ElementAbstract
{
    /* @var Options */
    protected $options;


    public function __construct(EscaperInterface $escaper, Options $options)
    {
        $this->options = $options;
        parent::__construct($escaper);
    }

    public function getOptionValues()
    {
        return $this->options->getValues();
    }

    protected function transferItemAttributes()
    {
        $attributesTransfer = ['readonly', 'disabled'];
        $transfers = [];

        foreach($attributesTransfer AS $attr){
            if($this->getAttributes()->has($attr) === false){
                continue;
            }

            if($this->getAttributes()->hasValue($attr) === true){
                $transfers[$attr] = $this->getAttributes()->get($attr);
            }else{
                $transfers[] = $attr;
            }

            $this->getAttributes()->remove($attr);
        }

        if(\count($transfers) === 0){
            return;
        }

        foreach($this->options AS $option){
            /* @var Option $option */
            $option->attributes()->add($transfers);
        }
    }

    abstract protected function optionItems();

    /**
     * @return string
     * @throws \GBrabyn\DynamicForms\Exception\DynamicFormsException
     */
    public function getWithoutErrorMessage()
    {
        $this->transferItemAttributes();
        $attributes = $this->getAttributesString();

        return '<ul'.($attributes ? ' '.$attributes : '').'><li>'.\implode('</li><li>', $this->optionItems()).'</li></ul>';
    }

    public function __clone()
    {
        $this->options = clone $this->options;
        parent::__clone();
    }
}