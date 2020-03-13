<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/2/18
 * Time: 3:52 PM
 */
use GBrabyn\DynamicForms;
use GBrabyn\DynamicForms\Field;

class CallbackElementTest extends PHPUnit_Framework_TestCase
{
    public function testGetWithoutErrorMessage()
    {
        $escaper = new DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );

        $obj = new DynamicForms\Element\Callback(function($fieldName, $field, $attributes, $escaper){
            /* @var $field Field
             * @var $attributes  DynamicForms\Element\Attributes
             * @var $escaper DynamicForms\Element\EscaperInterface
             */

            return '<div '.$attributes->getAsString().' name="'.$escaper->escapeAttr($fieldName).'">'.$escaper->escapeHtml($field->getValue()).'</div>';
        }, $escaper);

        $field = new Field();
        $field->setValue('xyz', false);

        $obj->setField($field);
        $obj->setName('bob');

        $attributes = new DynamicForms\Element\Attributes(['required', 'id'=>'b123', 'class'=>'green blue']);
        $obj->setAttributes($attributes);

        $element = (string)$obj;
        $expected = <<<EOT
<div id="b123" class="green blue" required name="bob">xyz</div>
EOT;
        $this->assertEquals(trim($expected), $element);
    }
}