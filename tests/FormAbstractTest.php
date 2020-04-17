<?php

/**
 * Description of FormAbstractTest
 *
 * @author GBrabyn
 */
class FormAbstractTest extends PHPUnit_Framework_TestCase 
{
    
    public function getClass()
    {
        return $this
                ->getMockBuilder('GBrabyn\DynamicForms\FormAbstract')
                ->getMockForAbstractClass();
    }
    
    
    public function invokeMethod($object, $methodName, array $parameters=[])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $parameters);
    }
    
    
    public function testIsThereAnySyntaxError()
    {
        $var = $this->getClass();
        $this->assertTrue(is_object($var));
    }
    
    
    public function testField()
    {
        $form = $this->getClass();
        
        $field = $form->field('aa.bb');
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $field);
        
        $field2 = $form->field('aa.bb');
        $this->assertTrue($field === $field2);
        
        $field3 = $form->field('ccc');
        $this->assertFalse($field === $field3);
    }


    public function testGetFields()
    {
        $form1 = $this->getClass();
        [$field1, $field2] = $this->invokeMethod($form1, 'getFields', ['11', '22']);

        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $field1);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $field2);

        $form2 = $this->getClass();
        $fieldA = $form2->field('A');
        $fieldB = $form2->field('B');

        [$field3, $field4] = $this->invokeMethod($form2, 'getFields', ['A', 'B']);

        $this->assertSame($fieldA, $field3);
        $this->assertSame($fieldB, $field4);
    }


    public function testFieldsValid()
    {
        $form1 = $this->getClass();
        $field1 = $form1->field('A');
        $field2 = $form1->field('B');

        $result1 = $this->invokeMethod($form1, 'fieldsValid', [$field1, $field2]);

        $this->assertTrue($result1);

        $error = new GBrabyn\DynamicForms\Error('Msg');
        $field2->addError($error);
        $result2 = $this->invokeMethod($form1, 'fieldsValid', [$field1, $field2]);

        $this->assertFalse($result2);
    }
    
    
    public function testPopulate()
    {
        $form = $this->getClass();
        
        $field1 = $form->field('aa.bb');
        $field2 = $form->field('aa.cc');
        $field3 = $form->field('aa.bb.dd');
        $field4 = $form->field('ccc');
        
        $post = [
            'aa' => [
                'bb' => [
                    'dd' => 'dd Value'
                ],
                'cc' => 'cc Value',
            ],
            'ccc' => 'ccc Value',
            'eee' => [
                'ff' => [
                    'gg' => ['l', 'm', 'n'],
                    'hh' => 888,
                ]
            ]
        ];
        
        $form->populate($post, false);
        
        $this->assertEquals(['dd' => 'dd Value'], $field1->getValue());
        $this->assertEquals('cc Value', $field2->getValue());
        $this->assertEquals('dd Value', $field3->getValue());
        $this->assertEquals('ccc Value', $field4->getValue());
    }
    
    
    public function testPopulateWithTransformers()
    {
        $form = $this->getClass();
        
        $transformer1 = new GBrabyn\DynamicForms\Transform\Trim();
        $transformer2 = new GBrabyn\DynamicForms\Transform\AnonymousFunctionTransform(function($value){
            return $value.' ZZ';
        });

        $form->addUniveralTransformers([$transformer1]);
        $form->addUniveralTransformers($transformer2);
        
        $field = $form->field('aa.bb');
        $post1 = [ 'aa' => ['bb' => ' blue lagoon 1  '] ];
        $post2 = [ 'aa' => ['bb' => ' Blue lagoon 2  ', 'cc' => ' 3 Lagoons blue  '], 'dd' => (new stdClass()) ];
        
        $form->populate($post1, false);
        $this->assertEquals(' blue lagoon 1  ', $field->getValue());
        
        $form->populate($post2, true);
        $this->assertEquals('Blue lagoon 2 ZZ', $field->getValue());
    }
    
    
    public function test_RemoveBranchKeys()
    {
        $form = $this->getClass();
        $keyList = ['aa', 'cc', 'aa.cc.dd'];

        $this->invokeMethod($form, '_removeBranchKeys', [&$keyList, 'aa.bb']);
        
        $this->assertEquals(['cc', 'aa.cc.dd'], array_values($keyList));
    }
    
    
    public function test_getEndNodeFieldKeys()
    {
        $form = $this->getClass();

        $field1 = $form->field('aa.cc');
        $field2 = $form->field('aa');
        $field3 = $form->field('aa.bb.dd');
        $field4 = $form->field('ccc');
        
        $endNodekeys = $this->invokeMethod($form, '_getEndNodeFieldKeys', []);
        
        $this->assertEquals(['aa.cc', 'aa.bb.dd', 'ccc'], $endNodekeys);
    }
    
    
    public function test_makeFieldKeyEntryInArray()
    {
        $form = $this->getClass();
        $field1 = $form->field('aa.bb.dd');
        $field1->setValue(555, false);
        
        $array = [
            'aa' => [
                'bb' => 99,
                'cc' => [
                    'dd' => 'blue',
                ]
            ],
            'ee' => 88,
        ];
        
        $output = $this->invokeMethod($form, '_makeFieldKeyEntryInArray', [$array, 'aa.bb.dd']);

        $expected = [
            'aa' => [
                'bb' => [
                    'dd' => 555,
                ],
                'cc' => [
                    'dd' => 'blue',
                ],
            ],
            'ee' => 88,
        ];
        
        $this->assertEquals($expected, $output);
        
        
        $field2 = $form->field('aa.0');
        $field2->setValue(null, false);
        
        $output2 = $this->invokeMethod($form, '_makeFieldKeyEntryInArray', [$array, 'aa.0']);
        
        $expected2 = [
            'aa' => [
                'bb' => 99,
                'cc' => [
                    'dd' => 'blue',
                ],
                0 => null,
            ],
            'ee' => 88,
        ];
        
        $this->assertEquals($expected2, $output2);
        
        $field3 = $form->field('ff.0');
        $field3->setValue(true, false);
        
        $output3 = $this->invokeMethod($form, '_makeFieldKeyEntryInArray', [$array, 'ff.0']);
        
        $expected3 = [
            'aa' => [
                'bb' => 99,
                'cc' => [
                    'dd' => 'blue',
                ]
            ],
            'ee' => 88,
            'ff' => [
                true
            ]
        ];
        
        $this->assertEquals($expected3, $output3);
        
        $field4 = $form->field('aa.cc');
        $field4->setValue(22, false);
        
        $output4 = $this->invokeMethod($form, '_makeFieldKeyEntryInArray', [$array, 'aa.cc']);

        $expected4 = [
            'aa' => [
                'bb' => 99,
                'cc' => 22
            ],
            'ee' => 88,
        ];
        
        $this->assertEquals($expected4, $output4);
    }

    
    public function testGetDefinedValues()
    {
        $form = $this->getClass();
        $field1 = $form->field('aa.bb');
        $post = [ 'aa' => ['bb' => 'Blue lagoon 2', 'cc' => '3 Lagoons blue'] ];
        $form->populate($post, false);
        
        $this->assertEquals([ 'aa' => ['bb' => 'Blue lagoon 2'] ], $form->getDefinedValues());
        
        $array = [
            'aa' => [
                'bb' => 99,
                'cc' => [
                    'dd' => 'blue',
                ]
            ],
            'ee' => 88,
        ];
        
        $form->populate($array, false);
        
        $this->assertEquals([ 'aa' => ['bb' => 99] ], $form->getDefinedValues());
        
        $field2 = $form->field('aa.cc');
        $field2->setValue(22, false);
        
        $field3 = $form->field('aa.cc.dd');
        
        $expected = [
            'aa' => [
                'bb' => 99,
                'cc' => [
                    'dd' => 'blue',
                ]
            ],
        ];

        $this->assertEquals($expected, $form->getDefinedValues());

        $form->removeField('aa.bb');
        $form->removeField('aa.cc');

        $expected2 = [
            'aa' => [
                'cc' => [
                    'dd' => 'blue',
                ]
            ],
        ];

        $this->assertEquals($expected2, $form->getDefinedValues());

        $form->removeField('aa.cc.dd');
        $this->assertEquals([], $form->getDefinedValues());
    }
    
    
    public function testGetValues()
    {
        $form = $this->getClass();
        
        $array = [
            'aa' => [
                'bb' => [
                    'dd' => 'dd Value'
                ],
                'cc' => 'cc Value',
            ],
            'ccc' => 'ccc Value',
            'eee' => [
                'ff' => [
                    'gg' => ['l', ' m ', '  '],
                    'hh' => 888,
                ]
            ],
        ];
        
        $field1 = $form->field('eee.ff.gg.2')->addTransformers([new \GBrabyn\DynamicForms\Transform\Trim(), new GBrabyn\DynamicForms\Transform\EmptyStringToNull()]);
        $form->populate($array, true);

        $field2 = $form->field('aa.cc')
                        ->addTransformers(new \GBrabyn\DynamicForms\Transform\Trim())
                        ->setValue('cc Value Enhanced ', true);
        
        $field3 = $form->field('aa.dd')->setValue('aa.dd Value', true);
        
        $expected = [
            'aa' => [
                'bb' => [
                    'dd' => 'dd Value'
                ],
                'cc' => 'cc Value Enhanced',
                'dd' => 'aa.dd Value',
            ],
            'ccc' => 'ccc Value',
            'eee' => [
                'ff' => [
                    'gg' => ['l', ' m ', null],
                    'hh' => 888,
                ]
            ],
        ];
        
        $this->assertEquals($expected, $form->getValues());
    }
    
    
    public function testValidate1()
    {
        $form = $this->getClass();
        
        $array = [
            'aa' => [
                'bb' => [
                    'dd' => 'dd Value'
                ],
                'cc' => 'cc Value',
            ],
            'ccc' => 'ccc Value',
            'eee' => [
                'ff' => [
                    'gg' => ['l', ' m ', '  '],
                    'hh' => 888,
                    'ii' => null,
                ]
            ],
        ];
        
        $field1 = $form->field('eee.ff.gg.2')
                        ->addTransformers([new \GBrabyn\DynamicForms\Transform\Trim()])
                        ->addValidators(new GBrabyn\DynamicForms\FieldValidator\Required());
        
        $field2 = $form->field('eee.ff.ii')
                        ->addTransformers([new \GBrabyn\DynamicForms\Transform\Trim()])
                        ->addValidators(new GBrabyn\DynamicForms\FieldValidator\Required());
        
        $form->populate($array, true);
        
        $this->assertFalse($form->validate());
    }
    
    
    public function testValidate2()
    {
        $form = $this->getClass();
        
        $array = [
            'aa' => [
                'bb' => [
                    'dd' => 'dd Value'
                ],
                'cc' => 'cc Value',
            ],
            'ccc' => 'ccc Value',
            'eee' => [
                'ff' => [
                    'gg' => ['l', ' m ', ' 1 '],
                    'hh' => 888,
                    'ii' => 0,
                ]
            ],
        ];
        
        $field1 = $form->field('eee.ff.gg.2')
                        ->addTransformers([new \GBrabyn\DynamicForms\Transform\Trim()])
                        ->addValidators(new GBrabyn\DynamicForms\FieldValidator\Required());
        
        $field2 = $form->field('eee.ff.ii')
                        ->addTransformers([new \GBrabyn\DynamicForms\Transform\Trim()])
                        ->addValidators(new GBrabyn\DynamicForms\FieldValidator\Required());
        
        $form->populate($array, true);
        
        $this->assertTrue($form->validate());
    }
    
    
    public function testGroupValidate1()
    {
        $form = $this->getClass();
        
        $array = [
            'aa' => [
                'bb' => [
                    'dd' => 'dd Value'
                ],
                'cc' => 'cc Value',
            ],
            'ccc' => 'ccc Value',
            'eee' => [
                'ff' => [
                    'gg' => ['l', ' m ', ' 1 '],
                    'hh' => 888,
                    'ii' => 0,
                ]
            ],
        ];
        
        $field1 = $form->field('eee.ff.gg.2');
        $field2 = $form->field('eee.ff.ii');
        
        $form->addValidator(new GBrabyn\DynamicForms\GroupValidator\MutuallyRequired([$field1, $field2]));
        $form->populate($array, false);
        
        $this->assertTrue($form->validate());
    }
    
    
    public function testGroupValidate2()
    {
        $form = $this->getClass();
        
        $array = [
            'aa' => [
                'bb' => [
                    'dd' => 'dd Value'
                ],
                'cc' => 'cc Value',
            ],
            'ccc' => 'ccc Value',
            'eee' => [
                'ff' => [
                    'gg' => ['l', ' m ', ' 1 '],
                    'hh' => 888,
                    'ii' => '',
                ]
            ],
        ];
        
        $field1 = $form->field('eee.ff.gg.2');
        $field2 = $form->field('eee.ff.ii');
        
        $form->addValidator(new GBrabyn\DynamicForms\GroupValidator\MutuallyRequired([$field1, $field2]));
        $form->populate($array, false);
        
        $this->assertFalse($form->validate());
    }
    
    
    public function testAddError()
    {
        $form = $this->getClass();
        
        $form->addError(null);
        
        $this->assertTrue($form->validate());
        $this->assertEquals(0, count($form->getErrors()));
        
        $error1 = new GBrabyn\DynamicForms\Error('blue');
        $error2 = new GBrabyn\DynamicForms\Error('blue');
        
        $form->addError($error1);
        $form->addError($error2);
        
        $this->assertFalse($form->validate());
        $this->assertEquals(1, count($form->getErrors()));
    }
    
    
    public function testElement1()
    {
        $form = $this->getClass();
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $elementDecorator = new GBrabyn\DynamicForms\Element\html5\Input($escaper, 'text');

        $element = $form->element($elementDecorator, 'aa[bb]', 'aa.bb', ['id'=>5], null);
        $expected = '<input type="text" name="aa[bb]" value="" id="5">';
        
        $this->assertEquals($expected, $element);
    }
    
    
    public function testElement2()
    {
        $form = $this->getClass();
        $form->setDefaultErrorDecorator(new \GBrabyn\DynamicForms\ErrorDecorator\html5\RightSide());
        
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $elementDecorator = new GBrabyn\DynamicForms\Element\html5\Input($escaper, 'text');
        $form->registerElement('inputText', $elementDecorator);
        
        $form->field('aa.bb')->addValidators(new GBrabyn\DynamicForms\FieldValidator\Required());
        $form->validate();
        
        $element = $form->element('inputText', 'aa[bb]', 'aa.bb', ['id'=>5], null);
        $expected = '<input type="text" name="aa[bb]" value="" id="5" class="error"><span class="error msgRight">Required</span>';
        
        $this->assertEquals($expected, $element);
    }
    
    
    public function testElementSelect()
    {
        $escaper = new GBrabyn\DynamicForms\Element\LaminasEscaperWrapper( new Laminas\Escaper\Escaper('utf-8') );
        $form = $this->getClass();
        $translator = $this
                        ->getMockBuilder('GBrabyn\DynamicForms\TranslatorInterface')
                        ->getMock();
        $translator->method('translate')->willReturn('Rubbish');

        $form->setDefaultErrorDecorator(new \GBrabyn\DynamicForms\ErrorDecorator\html5\RightSide($translator));
        $form->registerErrorDecorator('below', new \GBrabyn\DynamicForms\ErrorDecorator\html5\Below($translator));

        $options = new \GBrabyn\DynamicForms\Element\Options();
        $options->add(1, 'one', null, [])
                ->add(2, 'two')
                ->add(3, 'three');

        $select = new \GBrabyn\DynamicForms\Element\html5\Select($escaper, $options);
        $form->registerElement('selectNumber', $select);
        
        $output1 = $form->element('selectNumber', 'houseNumber', 'houseNumber', ['id'=>'houseNum'], 'below');
        $expected1 = '<select name="houseNumber" id="houseNum"><option value="1">one</option><option value="2">two</option><option value="3">three</option></select>';
        
        $this->assertEquals($expected1, $output1);
        
        $form->field('houseNumber')->addValidators([new \GBrabyn\DynamicForms\FieldValidator\Allowed($options->getValues())]);
        $form->populate(['houseNumber'=>4], true);
        $valid = $form->validate();
        
        $this->assertFalse($valid);
        
        $output2 = $form->element('selectNumber', 'houseNumber', 'houseNumber', ['id'=>'houseNum'], 'below');
        $expected2 = '<select name="houseNumber" id="houseNum" class="error"><option value="1">one</option><option value="2">two</option><option value="3">three</option></select>'
                . '<br><span class="error msgBelow">Rubbish</span>';
        
        $this->assertEquals($expected2, $output2);
    }

    public function testSeqKeyFields()
    {
        $form = $this->getClass();

        $formValues = [
            'aaa' => 'fasfasf',
            'bbb' => [
                'ccc' => -1,
            ],
            'ddd' => [
                '1.222,34',
                '1,55',
                null
            ],
            'eee' => [
                '9.123,12'
            ],
            'fff' => ' 10.123,26 ',
            [
                '111', null, false, 2222, '3333'
            ],
        ];

        $form->populate($formValues, false);

        $iterator1 = $form->seqKeyFields($formValues, 'ddd');

        [$ddd1] = $iterator1->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $ddd1);
        $this->assertEquals('1.222,34', $ddd1->getValue());

        $iterator1->next();
        [$ddd2] = $iterator1->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $ddd2);
        $this->assertEquals('1,55', $ddd2->getValue());

        $iterator1->next();
        [$ddd3] = $iterator1->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $ddd3);
        $this->assertNull($ddd3->getValue());

        $this->assertNull($iterator1->next());

        $iterator2 = $form->seqKeyFields($formValues, 'ddd', 'eee');
        [$ddd4, $eee1] = $iterator2->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $ddd4);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $eee1);
        $this->assertEquals('9.123,12', $eee1->getValue());

        $iterator3 = $form->seqKeyFields($formValues, 'bbb', 'eee');
        $this->assertNull($iterator3->current());
        $this->assertEquals([], $form->field('bbb')->getValue());
        $this->assertEquals([], $form->getDefinedValues()['bbb']);

        $iterator4 = $form->seqKeyFields($formValues, 'eee', 'bbb');
        [$eee2, $bbb1] = $iterator4->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $eee2);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $bbb1);
        $this->assertEquals('9.123,12', $eee2->getValue());
        $this->assertNull($bbb1->getValue());

        $iterator5 = $form->seqKeyFields($formValues, 'ddd', 'zzz');
        $this->assertNull($iterator5->current());
        $this->assertEquals([], $form->getDefinedValues()['zzz']);

        $iterator6 = $form->seqKeyFields($formValues, 'ddd', 'aaa');
        $this->assertNull($iterator6->current());
        $this->assertEquals([], $form->getDefinedValues()['aaa']);

        $iterator7 = $form->seqKeyFields($formValues, 'yyy', 'ddd');
        $this->assertNull($iterator7->current());
        $this->assertEquals([], $form->getDefinedValues()['yyy']);
    }


    public function testSeries()
    {
        $form = $this->getClass();

        $formValues = [
            'aaa' => 'fasfasf',
            'bbb' => [
                'ccc' => -1,
            ],
            'ddd' => [
                '1.222,34',
                '1,55',
                null
            ],
            'eee' => [
                '9.123,12'
            ],
            'fff' => [
                ['name'=>'bob', 'color'=>'blue'],
                ['name'=>'sue', 'color'=>'red'],
            ],

        ];

        $form->populate($formValues, false);

        $iterator1 = $form->series($formValues, 'aaa', 'ttt');
        $this->assertNull($iterator1->current());

        $iterator2 = $form->series($formValues, 'bbb', 'ccc');
        $this->assertNull($iterator2->current());

        $iterator3 = $form->series($formValues, 'zzz', 'ccc');
        $this->assertNull($iterator3->current());
        $this->assertEquals([], $form->getDefinedValues()['zzz']);

        $iterator4 = $form->series($formValues, 'ddd', 'ccc', 'ggg');
        [$ccc1] = $iterator4->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $ccc1);

        $iterator4->next();
        [$ccc2, $ggg2] = $iterator4->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $ccc2);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $ggg2);
        $this->assertNull($ccc2->getValue());

        $iterator4->next();
        [$ccc3, $ggg3] = $iterator4->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $ccc3);
        $this->assertNull($ccc3->getValue());

        $iterator4->next();
        $this->assertNull($iterator4->current());

        $iterator5 = $form->series($formValues, 'fff', 'name', 'color');
        [$name1, $color1] = $iterator5->current();
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $name1);
        $this->assertInstanceOf('\GBrabyn\DynamicForms\Field', $color1);
        $this->assertEquals('bob', $name1->getValue());
        $this->assertEquals('blue', $color1->getValue());

        $iterator5->next();
        [$name2, $color2] = $iterator5->current();
        $this->assertEquals('sue', $name2->getValue());
        $this->assertEquals('red', $color2->getValue());


    }


    public function testIntKeys()
    {
        $form = $this->getClass();

        $formValues = [
            'aaa' => 'fasfasf',
            'bbb' => [
                'ccc' => -1,
            ],
            'ddd' => [
                '1.222,34',
                '1,55',
                null
            ],
            'eee' => [
                '9.123,12'
            ],
            'fff' => ' 10.123,26 ',
            [
                '111', null, false, 2222, '3333'
            ],
            'ggg' => [
                '-2' => 444,
            ],
            'hhh' => [
                'iii' => [
                    8, 7, 6, -3=>5, 1=>4
                ],
            ],
        ];

        $this->assertEquals([], $form->intKeys($formValues, 'zzz'));
        $this->assertEquals([], $form->intKeys($formValues, 'aaa'));
        $this->assertEquals([], $form->intKeys($formValues, 'bbb'));
        $this->assertEquals([0,1,2], $form->intKeys($formValues, 'ddd'));
        $this->assertEquals([0], $form->intKeys($formValues, 'eee'));
        $this->assertEquals([], $form->intKeys($formValues, 'fff'));
        $this->assertEquals([0,1,2,3,4], $form->intKeys($formValues, '0'));
        $this->assertEquals([], $form->intKeys($formValues, 'ggg'));
        $this->assertEquals([0,1,2], $form->intKeys($formValues, 'hhh.iii'));
    }
    
    
    public function testfieldError()
    {
        $form = $this->getClass();
        $separateDecorator = new GBrabyn\DynamicForms\ErrorDecorator\html5\Separate();

        $form->registerStandAloneErrorDecorator('separate', $separateDecorator);
        $form->populate(['bbb'=>''], false);
        $form->field('bbb')
                ->addValidators(new GBrabyn\DynamicForms\FieldValidator\Required());
        
        $form->validate();
        
        $this->assertEquals('', $form->fieldError('aaa', $separateDecorator));

        $this->assertEquals('<span class="error msgSeparate">Required</span>', $form->fieldError('bbb', 'separate'));
        
        $form->setDefaultStandAloneErrorDecorator($separateDecorator);
        
        $this->assertEquals('<span class="error msgSeparate">Required</span>', $form->fieldError('bbb'));
    }
    
    
    public function testErrors()
    {
        $form = $this->getClass();
        $formErrorsDecorator = new GBrabyn\DynamicForms\ErrorDecorator\html5\Form();
        $form->setFormErrorsDecorator($formErrorsDecorator);
        
        $this->assertEquals('', $form->errors());
        
        $form->populate(['bbb'=>''], false);
        $form->field('bbb')
                ->addValidators(new GBrabyn\DynamicForms\FieldValidator\Required());
        
        $form->validate();
        
        $expected1 = '<p class="errors">
Please fix the errors in the form below.
</p>';
        
        $this->assertEquals($expected1, $form->errors());
        
        $error1 = new GBrabyn\DynamicForms\Error('Form wide error message1');
        $form->addError($error1);
        
        $expected2 = '<p class="errors">
Please fix the errors in the form below.
<br>Form wide error message1
</p>';
        
        $this->assertEquals($expected2, $form->errors());
        
        $error2 = new GBrabyn\DynamicForms\Error('Form wide error message2');
        $form->addError($error2);
        
        $expected3 = '<p class="errors">
Please fix the errors in the form below.
<br>Form wide error message1<br>
Form wide error message2
</p>';
        
        $this->assertEquals($expected3, $form->errors());
    }
    
}
