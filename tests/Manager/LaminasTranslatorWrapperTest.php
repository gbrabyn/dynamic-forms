<?php

use GBrabyn\DynamicForms\Manager\LaminasTranslatorWrapper;

class LaminasTranslatorWrapperTest extends PHPUnit_Framework_TestCase
{
    public function getTranslator()
    {
        return $this
            ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
            ->getMock();
    }

    public function testTranslate()
    {
        $translator = $this->getTranslator();
        $translator->method('translate')->willReturn('ABC');

        $laminasTranslatorWrapper = new LaminasTranslatorWrapper($translator);

        $this->assertEquals('ABC', $laminasTranslatorWrapper->translate('xyz'));
    }
}