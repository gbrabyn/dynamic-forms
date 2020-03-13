<?php
namespace GBrabyn\DynamicForms\Element;

use GBrabyn\DynamicForms\Element\{ElementAbstract, EscaperInterface, Attributes};
use GBrabyn\DynamicForms\{Field};

/**
 * Allows use of a callback function to produce an HTML form element
 *
 * @author GBrabyn
 */
class Callback extends ElementAbstract 
{
    /**
     *
     * @var Callable
     */
    private $callable;

    /**
     * 
     * @param Callable $callable - function(string $fieldName, Field $field, Attributes $attributes, EscaperInterface $escaper)
     * @param TranslatorInterface $translator
     */
    public function __construct(Callable $callable, EscaperInterface $escaper)
    {
        $this->callable = $callable;
        parent::__construct($escaper);
    }

    /**
     * 
     * @return string
     */
    public function getWithoutErrorMessage()
    {
        $callable = $this->callable;
        
        return $callable($this->fieldName, $this->field, $this->getAttributes(), $this->getEscaper());
    }
}
