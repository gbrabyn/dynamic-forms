<?php


namespace GBrabyn\DynamicForms\Component;

use GBrabyn\DynamicForms\Element\Options;

interface ComponentUserInterface
{
    public function getComponent(string $propertyName) : ComponentAbstract;

    public function getOptions(string $propertyName) : Options;

    public function activateComponents();
}