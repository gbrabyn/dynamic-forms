<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 12/17/18
 * Time: 5:42 PM
 */

class FormUsingViewWrapperAbstractTest extends PHPUnit_Framework_TestCase
{
    public function getClass()
    {
        return $this
            ->getMockBuilder('GBrabyn\DynamicForms\FormUsingViewWrapperAbstract')
            ->getMockForAbstractClass();
    }

    public function getViewWrapperAbstract()
    {
        return $this
            ->getMockBuilder('GBrabyn\DynamicForms\ViewWrapper\ViewWrapperAbstract')
            ->disableOriginalConstructor()
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

    public function testActivateHasSyntaxError()
    {
        $obj = $this->getClass();

        $this->invokeMethod($obj, '_activate');
        $this->assertTrue(is_object($obj));

        $this->invokeMethod($obj, 'update', [$obj, 'populateStart']);
    }

    public function testViewWrapper()
    {
        $obj = $this->getClass();
        $obj->method('getViewWrapper')->willReturn($this->getViewWrapperAbstract());

        $escaper = new \GBrabyn\DynamicForms\Element\LaminasEscaperWrapper(new \Laminas\Escaper\Escaper());

        $obj->setEscaper($escaper);
        $obj->setElementManager(new \GBrabyn\DynamicForms\Manager\ElementManager('html5', $escaper, 'en_US'));
        $obj->setErrorDecoratorManager(new \GBrabyn\DynamicForms\Manager\ErrorDecoratorManager('html5'));
        $obj->setTransformerManager(new \GBrabyn\DynamicForms\Manager\TransformManager());
        $obj->setValidatorManager(new \GBrabyn\DynamicForms\Manager\ValidatorManager());

        $this->assertInstanceOf(\GBrabyn\DynamicForms\ViewWrapper\ViewWrapperAbstract::class, $obj->viewWrapper());
    }
}