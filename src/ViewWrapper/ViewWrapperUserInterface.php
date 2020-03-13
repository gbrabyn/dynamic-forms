<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/5/18
 * Time: 3:26 PM
 */

namespace GBrabyn\DynamicForms\ViewWrapper;


interface ViewWrapperUserInterface
{
    public function viewWrapper() : ViewWrapperAbstract;

    public function getOptions(string $propertyName) : \GBrabyn\DynamicForms\Element\Options;
}