<?php
/**
 * User: GBrabyn
 */

namespace GBrabyn\DynamicForms\tests;

include realpath(__DIR__ . '/Component/NumbersComponent.php');

use GBrabyn\DynamicForms\FormUsingViewWrapperAbstract AS FormAbstract;
use GBrabyn\DynamicForms\Element\Options;
use GBrabyn\DynamicForms\tests\Component\NumbersComponent;

abstract class FormUsingViewWrapperAbstract extends FormAbstract
{
    protected $booleanOptions;

    /** @var Component\Component */
    protected $numbersComponent;


    protected function activate()
    {
        $this->setBooleanOptions();
        $this->setNumbersComponent();
    }

    private function setNumbersComponent()
    {
        $this->numbersComponent = new NumbersComponent();
    }

    private function setBooleanOptions()
    {
        $options = new Options();
        $options->add(1, 'Yes');
        $options->add(0, 'No');

        $this->booleanOptions = $options;
    }

    protected function config(array $formValues)
    {
        $this->numbersComponent->configure('aaa', $formValues);
        $this->numbersComponent->configure(null, $formValues);
    }
}