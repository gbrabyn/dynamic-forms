<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 12/27/18
 * Time: 4:03 PM
 */

require_once realpath(__DIR__.'/../FormUsingViewWrapperAbstract.php');

use GBrabyn\DynamicForms\Manager\ElementManager;
use GBrabyn\DynamicForms\Manager\ErrorDecoratorManager;
use GBrabyn\DynamicForms\Element\LaminasEscaperWrapper;
use Laminas\Escaper\Escaper as LaminasEscaper;
use GBrabyn\DynamicForms\Exception\DynamicFormsException;
use GBrabyn\DynamicForms\Field;
use GBrabyn\DynamicForms\tests\FormUsingViewWrapperAbstract;

class Html5ViewWrapperTest extends PHPUnit_Framework_TestCase
{
    private $form;

    public function getForm()
    {
        return $this
            ->getMockBuilder(FormUsingViewWrapperAbstract::class)
            ->getMockForAbstractClass();
    }

    public function getObj()
    {
        $this->form = $this->getForm();
        $laminasEscaper = new LaminasEscaper('utf-8');
        $escaperWrapper = new LaminasEscaperWrapper($laminasEscaper);
        $elements = new ElementManager('HTML5', $escaperWrapper, 'en_US');
        $errors = new ErrorDecoratorManager('HTML5');

        $this->form->registerElement('abc', $elements->hidden('25'));
        $this->form->populate(['e'=>['bob'=>'555'], 'c'=>['a', 'b'], 'f'=>[]], true);

        return new \GBrabyn\DynamicForms\ViewWrapper\Html5ViewWrapper($this->form, $elements, $errors);
    }

    public function testText()
    {
        $obj = $this->getObj();
        $expected = '<input type="text" name="e[test]" value="" id="_12">';

        $this->assertEquals($expected, $obj->text('e.test', ['id'=>'_12']));
    }

    public function testCustomElement()
    {
        $obj = $this->getObj();
        $expected = '<input type="hidden" name="e[test]" value="25" class="blue">';

        $this->assertEquals($expected, $obj->customElement('abc', 'e.test', ['class'=>'blue']));

        $this->expectException(DynamicFormsException::class);
        $obj->customElement('xyz', 'e.test');
    }

    public function testField()
    {
        $obj = $this->getObj();
        $this->assertInstanceOf(Field::class, $obj->field('aaa'));
    }

    public function testComponent()
    {
        $obj = $this->getObj();

        $viewWrapperWrapper = $obj->component('numbersComponent', null);
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Component\ViewWrapperWrapper::class, $viewWrapperWrapper);

        $this->expectException(\InvalidArgumentException::class);
        $obj->component('someComponent', 'aaa');
    }

    public function testErrorDecorators()
    {
        $obj = $this->getObj();
        $this->assertInstanceOf(ErrorDecoratorManager::class, $obj->errorDecorators());
        $this->assertInstanceOf(ErrorDecoratorManager::class, $obj->errDec());
    }

    public function testGetAllFieldErrors()
    {
        $obj = $this->getObj();
        $this->assertEquals([], $obj->getAllFieldErrors());

        $obj->field('aaa')->addValidators([new \GBrabyn\DynamicForms\FieldValidator\Required()]);
        $this->form->validate();

        $this->assertArrayHasKey('aaa', $obj->getAllFieldErrors());
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Error::class, $obj->getAllFieldErrors()['aaa'][0]);
    }

    public function testFieldError()
    {
        $obj = $this->getObj();
        $obj->setDefaultStandAloneErrorDecorator(new \GBrabyn\DynamicForms\ErrorDecorator\html5\Separate());
        $this->assertEmpty($obj->fieldError('aaa'));

        $obj->field('aaa')->addValidators([new \GBrabyn\DynamicForms\FieldValidator\Required()]);
        $this->form->validate();

        $this->assertEquals('<span class="error msgSeparate">Required</span>', $obj->fieldError('aaa'));
        $this->assertEquals('Required', $obj->fieldError('aaa', new \GBrabyn\DynamicForms\ErrorDecorator\SeparateRaw()));
    }

    public function testErrors()
    {
        $obj = $this->getObj();
        $obj->setFormErrorsDecorator(new \GBrabyn\DynamicForms\ErrorDecorator\html5\Form());
        $this->assertEmpty($obj->errors());

        $obj->field('aaa')->addValidators([new \GBrabyn\DynamicForms\FieldValidator\Required()]);
        $this->form->validate();

        $this->assertEquals('<p class="errors">
Please fix the errors in the form below.
</p>', $obj->errors());
    }

    public function testNextKey()
    {
        $obj = $this->getObj();
        $this->assertEquals(2, $obj->nextKey('c'));
        $this->assertEquals(0, $obj->nextKey('d'));
        $this->assertEquals(0, $obj->nextKey('f'));

        $this->expectException(\InvalidArgumentException::class);
        $obj->nextKey('e.bob');
    }

    public function testIntKeys()
    {
        $obj = $this->getObj();
        $this->assertEquals([0,1], $obj->intKeys('c'));
    }

    public function testCheckbox()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="checkbox" name="bbb[ccc]" value="20">', $obj->checkbox('bbb.ccc', 20));
        $this->assertEquals('<input type="checkbox" name="e[bob]" value="555" checked>', $obj->checkbox('e.bob', 555));
    }

    public function testCheckboxList()
    {
        $obj = $this->getObj();

        $expected1 = '<ul><li><label><input type="checkbox" name="aaa[]" value="1">Yes</label></li><li><label><input type="checkbox" name="aaa[]" value="0">No</label></li></ul>';
        $this->assertEquals($expected1, $obj->checkboxList('booleanOptions', 'aaa'));

        $expected2 = '<ul id="abc" class="wow"><li><label><input type="checkbox" name="aaa[]" value="1">Yes</label></li><li><label><input type="checkbox" name="aaa[]" value="0">No</label></li></ul>';
        $this->assertEquals($expected2, $obj->checkboxList('booleanOptions', 'aaa', ['id'=>'abc', 'class'=>'wow']));

        $obj->field('aaa')->addValidators([new \GBrabyn\DynamicForms\FieldValidator\Required()]);
        $this->form->validate();

        $expected3 = '<span class="error msgAbove">Required</span><br><ul class="error"><li><label><input type="checkbox" name="aaa[]" value="1">Yes</label></li><li><label><input type="checkbox" name="aaa[]" value="0">No</label></li></ul>';
        $this->assertEquals($expected3, $obj->checkboxList('booleanOptions', 'aaa', [], new \GBrabyn\DynamicForms\ErrorDecorator\html5\Above()));

        $this->expectException(\InvalidArgumentException::class);
        $obj->checkboxList('abcOptions', 'aaa');
    }

    public function testHidden()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="hidden" name="e[bob]" value="20">', $obj->hidden('e.bob', ['value'=>20]));
        $this->assertEquals('<input type="hidden" name="e[bob]" value="555" id="_12">', $obj->hidden('e.bob', ['id'=>'_12']));
    }

    public function testPassword()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="password" name="aaa" value="" id="password">', $obj->password('aaa', ['id'=>'password']));

        $obj->setDefaultErrorDecorator(new \GBrabyn\DynamicForms\ErrorDecorator\html5\RightSide());
        $obj->field('aaa')->addValidators([new \GBrabyn\DynamicForms\FieldValidator\Required()]);
        $this->form->validate();

        $this->assertEquals('<input type="password" name="aaa" value="" class="error"><span class="error msgRight">Required</span>', $obj->password('aaa'));
    }

    public function testFile()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="file" name="aaa" >', $obj->file('aaa'));
    }

    public function testRadio()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="radio" name="aaa" value="25" >', $obj->radio('aaa', 25));
    }

    public function testRadioList()
    {
        $obj = $this->getObj();

        $expected1 = '<ul><li><label><input type="radio" name="aaa" value="1">Yes</label></li><li><label><input type="radio" name="aaa" value="0">No</label></li></ul>';
        $this->assertEquals($expected1, $obj->radioList('booleanOptions', 'aaa'));
    }

    public function testSelect()
    {
        $obj = $this->getObj();
        $expected = '<select name="aaa" ><option value="1">Yes</option><option value="0">No</option></select>';
        $this->assertEquals($expected, $obj->select('booleanOptions', 'aaa'));
    }

    public function testTextarea()
    {
        $obj = $this->getObj();
        $this->assertEquals('<textarea name="aaa" ></textarea>', $obj->textarea('aaa'));
        $this->assertEquals('<textarea name="e[bob]" id="_12">555</textarea>', $obj->textarea('e.bob', ['id'=>'_12']));
    }

    public function testColor()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="color" name="aaa" value="" >', $obj->color('aaa'));
    }

    public function testDataList()
    {
        $obj = $this->getObj();
        $expected = '<datalist id="aaa"><option value="1">Yes</option><option value="0">No</option></datalist>';
        $this->assertEquals($expected, $obj->datalist('booleanOptions', ['id'=>'aaa']));
    }

    public function testDate()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="date" name="e[bob]" value="555" >', $obj->date('e.bob'));
    }

    public function testEmail()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="email" name="e[bob]" value="555" >', $obj->email('e.bob'));
    }

    public function testNumber()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="number" name="e[bob]" value="555" >', $obj->number('e.bob'));
    }

    public function testRange()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="range" name="e[bob]" value="555" >', $obj->range('e.bob'));
    }

    public function testSearch()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="search" name="e[bob]" value="555" >', $obj->search('e.bob'));
    }

    public function testTime()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="time" name="e[bob]" value="555" >', $obj->time('e.bob'));
    }

    public function testUrl()
    {
        $obj = $this->getObj();
        $this->assertEquals('<input type="url" name="e[bob]" value="555" >', $obj->url('e.bob'));
    }
}