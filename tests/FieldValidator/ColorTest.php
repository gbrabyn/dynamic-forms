<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/7/18
 * Time: 5:37 PM
 */

class ColorTest extends PHPUnit_Framework_TestCase
{
    public function testIsValid()
    {
        $obj = new GBrabyn\DynamicForms\FieldValidator\Color();

        $obj->setValue('#fe1099');
        $this->assertTrue($obj->isValid());

        $obj->setValue('#ABC123');
        $this->assertTrue($obj->isValid());

        $obj->setValue(1.03);
        $this->assertFalse($obj->isValid());

        $obj->setValue(null);
        $this->assertFalse($obj->isValid());

        $obj->setValue('');
        $this->assertFalse($obj->isValid());

        $obj->setValue('fe1099');
        $this->assertFalse($obj->isValid());

        $obj->setValue('#fe10991');
        $this->assertFalse($obj->isValid());

        $obj->setValue('fe10991');
        $this->assertFalse($obj->isValid());
    }
}