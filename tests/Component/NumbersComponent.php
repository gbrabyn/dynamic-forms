<?php
namespace GBrabyn\DynamicForms\tests\Component;

require_once realpath(__DIR__ . '/ListComponent.php');

use GBrabyn\DynamicForms\Component\ComponentAbstract;
use GBrabyn\DynamicForms\Element\Options;
use GBrabyn\DynamicForms\Error;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\GroupValidator\Callback;

class NumbersComponent extends ComponentAbstract
{
    /** @var Options */
    protected $numberOptions;

    protected $listComponent;

    private function setNumberOptions()
    {
        $this->numberOptions = new Options(range(0,9));
    }

    protected function activate()
    {
        $this->setNumberOptions();
        $this->setComponent();
    }

    private function setComponent()
    {
        $this->listComponent = new ListComponent();
    }

    protected function config(array $formValues, $options=[])
    {
        [$people] = $this->getFields('people');

        $people
            ->addTransformers([
                $this->transformers->trim(),
            ])
            ->addValidators([
                $this->validators->required(),
                $this->validators->allowed($this->numberOptions->getValues()),
            ]);

        $this->addValidator($this->primeNumberValidator($people));

        if($this->isset('e.bob', $formValues) && $this->getFromArray('e.bob', $formValues) != '555'){
            $this->addError(new Error('e.bob must = 555'));
        }

        if($this->getFromArray('xyz', $formValues) !== null){
            $this->addError(new Error('xyz must be null'));
        }

        foreach($this->intKeys($formValues, 'f') as $key){
            $this->field('f.'.$key)->addValidators([
                $this->validators->integer(),
            ]);
        }

        foreach($this->series($formValues, 'staff', 'name') AS [$name]){
            $name->addValidators([
                $this->validators->required(),
            ]);
        }

        $this->listComponent->configure(null, $formValues);
    }

    protected function primeNumberValidator(Field $people)
    {
        return new Callback(function($args){
            $people = $args['people'];

            if(empty($people->getValue())){
                return true;
            }

            return in_array($people->getValue(), [2,3,5,7,11,13,17,19]);
        }, new Error('Not a prime number'), ['people'=>$people]);
    }
}