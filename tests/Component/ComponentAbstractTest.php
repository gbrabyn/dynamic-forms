<?php

use GBrabyn\DynamicForms\tests\FormUsingViewWrapperAbstract;

class ComponentAbstractTest extends PHPUnit_Framework_TestCase
{
    public function getForm()
    {
        return $this
            ->getMockBuilder(FormUsingViewWrapperAbstract::class)
            ->getMockForAbstractClass();
    }

    public function testConfigure()
    {
        $form1 = $this->getForm();
        $form1->populate(['e'=>['bob'=>'555'], 'c'=>['a', 'b'], 'f'=>[]], true);

        $this->assertFalse($form1->validate());

        $form2 = $this->getForm();
        $form2->populate(['e'=>['bob'=>'555'], 'aaa'=>['people'=>7], 'people'=>5], true);

        $this->assertTrue($form2->validate());
    }

    public function testAddValidator()
    {
        $form3 = $this->getForm();
        $form3->populate(['e'=>['bob'=>'555'], 'aaa'=>['people'=>8]], true);

        $this->assertFalse($form3->validate());
    }

    public function testAddError()
    {
        $form4 = $this->getForm();
        $form4->populate(['aaa'=>['people'=>7, 'e'=>['bob'=>'666']]], true);

        $this->assertFalse($form4->validate());
    }

    public function testIntKeys()
    {
        $form5 = $this->getForm();
        $form5->populate(['e'=>['bob'=>'555'], 'aaa'=>['people'=>7, 'f'=>[1,2,3]], 'people'=>5], true);

        $this->assertTrue($form5->validate());
    }

    public function testSeries()
    {
        $form1 = $this->getForm();
        $form1->populate(['e'=>['bob'=>'555'], 'aaa'=>['people'=>7, 'staff'=>[['name'=>'aaa'], ['name'=>'bbb']]], 'people'=>5], true);

        $this->assertTrue($form1->validate());

        $form2 = $this->getForm();
        $form2->populate(['e'=>['bob'=>'555'], 'aaa'=>['people'=>7, 'staff'=>[['name'=>'aaa'], ['name'=>'']]], 'people'=>5], true);

        $this->assertFalse($form2->validate());
    }
}