<?php
/**
 * User: GBrabyn
 */
use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\ErrorDecorator\PlaceholderStandAloneDecorator;
use GBrabyn\DynamicForms\Error;

class PlaceholderStandAloneDecoratorTest extends TestCase
{
    public function getTranslator()
    {
        return $this
            ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
            ->getMock();
    }

    public function testToString()
    {
        $decorator = new PlaceholderStandAloneDecorator('aaa${error}bbb');

        $decorator->setErrors([new Error('Custom Message!')]);

        $expected = 'aaaCustom Message!bbb';

        $this->assertEquals($expected, (string)$decorator);
    }

    public function testTranslations()
    {
        $translator = $this->getTranslator();
        $translator->method('translate')->willReturn('Translated Message!');

        $decorator = new PlaceholderStandAloneDecorator('aaa${error}bbb${error}', $translator);

        $decorator->setErrors([new Error('Custom Message!', 'customMessage')]);

        $expected = 'aaaTranslated Message!bbbTranslated Message!';

        $this->assertEquals($expected, (string)$decorator);

    }
}