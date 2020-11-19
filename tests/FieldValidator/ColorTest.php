<?php
/**
 * User: GBrabyn
 */

use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
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