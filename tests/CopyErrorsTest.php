<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 9/14/18
 * Time: 2:50 PM
 */

class CopyErrorsTest extends PHPUnit_Framework_TestCase
{
    public function testTransferErrors()
    {
        $fieldFrom = new GBrabyn\DynamicForms\Field('field1');
        $fieldTo = new GBrabyn\DynamicForms\Field('field2');

        $this->assertEmpty($fieldTo->getErrors());

        $mockError = $this
            ->getMockBuilder('\GBrabyn\DynamicForms\Error')
            ->disableOriginalConstructor()
            ->getMock();

        new GBrabyn\DynamicForms\CopyErrors($fieldFrom, $fieldTo);

        $fieldFrom->addError($mockError);

        $this->assertSame($mockError, $fieldTo->getErrors()[0]);
    }
}