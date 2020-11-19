<?php
/**
 * User: GBrabyn
 */

use PHPUnit\Framework\TestCase;
use GBrabyn\DynamicForms\Manager\ElementManager;
use GBrabyn\DynamicForms\Element\LaminasEscaperWrapper;
use Laminas\Escaper\Escaper as LaminasEscaper;
use GBrabyn\DynamicForms\Element\Options;

class ElementManagerTest extends TestCase
{


    public function testCheckbox()
    {
        $laminasEscaper = new LaminasEscaper('utf-8');
        $escaperWrapper = new LaminasEscaperWrapper($laminasEscaper);

        $obj = new ElementManager('HTML5', $escaperWrapper, null);

        $checkbox = $obj->checkbox('true');

        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\Checkbox::class, $checkbox);
    }

    public function testCheckboxList()
    {
        $laminasEscaper = new LaminasEscaper('utf-8');
        $escaperWrapper = new LaminasEscaperWrapper($laminasEscaper);

        $obj = new ElementManager('HTML5', $escaperWrapper, 'de_DE');

        $options = new Options(['one', 'two', 'three']);
        $checkboxList = $obj->checkboxList($options);

        $this->assertInstanceOf(\GBrabyn\DynamicForms\Element\html5\checkboxList::class, $checkboxList);
    }
}