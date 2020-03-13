<?php
namespace GBrabyn\DynamicForms\Transform;

/**
 * Used to turn a callable function/method into a TransformAbstract instance
 *
 * @author GBrabyn
 */
class CallableTransform extends TransformAbstract 
{
    /**
     *
     * @var Callable
     */
    private $callable;

    /**
     * 
     * @param \Callable $callable - takes single argument of field value
     */
    public function __construct(Callable $callable)
    {
        $this->callable = $callable;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getValue()
    {
        $callable = $this->callable;
        
        return $callable($this->value);
    }
}
