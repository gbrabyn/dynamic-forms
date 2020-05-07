<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/2/18
 * Time: 3:25 PM
 */
use GBrabyn\DynamicForms\ErrorDecorator\StandAloneCallbackDecorator;
use GBrabyn\DynamicForms\Error;


class StandAloneCallbackDecoratorTest extends PHPUnit_Framework_TestCase
{
    public function getTranslator()
    {
        return $this
            ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
            ->getMock();
    }


    public function testToString()
    {
        $callbackDecorator = new StandAloneCallbackDecorator(function ($errorMessages) {

            return 'aaa' . $errorMessages[0] . 'bbb';
        });

        $callbackDecorator->setErrors([new Error('Custom Message!')]);

        $expected = 'aaaCustom Message!bbb';

        $this->assertEquals($expected, (string)$callbackDecorator);
    }

    public function testTranslations()
    {
        $translator = $this->getTranslator();
        $translator->method('translate')->willReturn('Translated Message!');

        $callbackDecorator = new StandAloneCallbackDecorator(function ($errorMessages, $translator) {
            /* @var $translator \GBrabyn\DynamicForms\TranslatorInterface */

            return 'aaa' . $errorMessages[0] . 'bbb'.$translator->translate('Appended sentence!');
        }, $translator);

        $callbackDecorator->setErrors([new Error('Custom Message!', 'customKey')]);

        $expected = 'aaaTranslated Message!bbbTranslated Message!';

        $this->assertEquals($expected, (string)$callbackDecorator);

    }
}