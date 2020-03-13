<?php
require_once realpath(__DIR__.'/../FormUsingViewWrapperAbstract.php');

use GBrabyn\DynamicForms\Element\LaminasEscaperWrapper;
use GBrabyn\DynamicForms\Manager\ElementManager;
use GBrabyn\DynamicForms\Manager\ErrorDecoratorManager;
use GBrabyn\DynamicForms\tests\FormUsingViewWrapperAbstract;
use Laminas\Escaper\Escaper as LaminasEscaper;
use GBrabyn\DynamicForms\Component\ViewWrapperWrapper;

class ViewWrapperWrapperTest extends PHPUnit_Framework_TestCase
{
    private function getForm()
    {
        return $this
            ->getMockBuilder(FormUsingViewWrapperAbstract::class)
            ->getMockForAbstractClass();
    }

    public function getViewWrapper($form)
    {
        $laminasEscaper = new LaminasEscaper('utf-8');
        $escaperWrapper = new LaminasEscaperWrapper($laminasEscaper);
        $elements = new ElementManager('HTML5', $escaperWrapper, 'en_US');
        $errors = new ErrorDecoratorManager('HTML5');

        return new \GBrabyn\DynamicForms\ViewWrapper\Html5ViewWrapper($form, $elements, $errors);
    }

    public function testIsThereAnySyntaxError()
    {
        $form = $this->getForm();
        $comp = $form->viewWrapper()->component('numbersComponent', null);
        $this->assertInstanceOf(\GBrabyn\DynamicForms\Component\ViewWrapperWrapper::class, $comp);
    }

    public function  testCheckboxList()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', 'aaa');

        $expected = '<ul><li><label><input type="checkbox" name="aaa[bbb][]" value="0">0</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="1">1</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="2">2</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="3">3</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="4">4</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="5">5</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="6">6</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="7">7</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="8">8</label></li><li><label><input type="checkbox" name="aaa[bbb][]" value="9">9</label></li></ul>';
        $this->assertEquals($expected, $comp->checkboxList('numberOptions', 'bbb', [], null));
    }

    public function testRadioList()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', null);

        $expected = '<ul><li><label><input type="radio" name="bbb[]" value="0">0</label></li><li><label><input type="radio" name="bbb[]" value="1">1</label></li><li><label><input type="radio" name="bbb[]" value="2">2</label></li><li><label><input type="radio" name="bbb[]" value="3">3</label></li><li><label><input type="radio" name="bbb[]" value="4">4</label></li><li><label><input type="radio" name="bbb[]" value="5">5</label></li><li><label><input type="radio" name="bbb[]" value="6">6</label></li><li><label><input type="radio" name="bbb[]" value="7">7</label></li><li><label><input type="radio" name="bbb[]" value="8">8</label></li><li><label><input type="radio" name="bbb[]" value="9">9</label></li></ul>';
        $this->assertEquals($expected, $comp->radioList('numberOptions', 'bbb.', [], null));
    }

    public function testSelect()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', null);

        $expected = '<select name="ccc" ><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option></select>';
        $this->assertEquals($expected, $comp->select('numberOptions', 'bbb.', ['name'=>'ccc'], null));
    }

    public function testText()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', 'aaa');

        $expected = '<input type="text" name="aaa[bbb]" value="" >';
        $this->assertEquals($expected, $comp->text('bbb', [], null));
    }

    public function testHidden()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', 'aaa');

        $expected = '<input type="hidden" name="aaa[ccc]" value="999" id="xyz">';
        $this->assertEquals($expected, $comp->hidden('bbb', ['name'=>'ccc', 'value'=>'999', 'id'=>'xyz'], null));
    }

    public function testBadMethodCall()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', 'aaa');

        $this->expectException(\BadMethodCallException::class);
        $comp->xyz('a', 'b');
    }

    public function testComponent()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', 'aaa');
        $listComp = $comp->component('listComponent', null);

        $this->assertInstanceOf(ViewWrapperWrapper::class, $listComp);
        $this->assertEquals('<input type="checkbox" name="aaa[ggg][]" value="999">', $listComp->checkbox('ggg', 999, ['name'=>'ggg[]']));
    }

    public function testComponent2()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', 'aaa');
        $listComp = $comp->component('listComponent', 'bbb');

        $this->assertInstanceOf(ViewWrapperWrapper::class, $listComp);
        $this->assertEquals('<input type="checkbox" name="aaa[bbb][ggg][hhh][]" value="999">', $listComp->checkbox('ggg.hhh.', 999));
        $this->assertEquals('<input type="radio" name="aaa[bbb][ggg][hhh][iii]" value="999" >', $listComp->radio('ggg.hhh', 999, ['name'=>'ggg[hhh][iii]']));
    }

    public function testErrorDecorators()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate([], true);
        $comp = $viewWrapper->component('numbersComponent', 'aaa');

        $this->assertInstanceOf(ErrorDecoratorManager::class, $comp->errorDecorators());
    }

    public function testNextKey()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate(['aaa'=>['bbb'=>['a','b']]], true);
        $comp = $viewWrapper->component('numbersComponent', 'aaa');

        $this->assertEquals(2, $comp->nextKey('bbb'));
    }

    public function testIntKeys()
    {
        $form = $this->getForm();
        $viewWrapper = $this->getViewWrapper($form);
        $form->populate(['bbb'=>['a','b']], true);
        $comp = $viewWrapper->component('numbersComponent', null);

        $this->assertEquals([0,1], $comp->intKeys('bbb'));
    }

}