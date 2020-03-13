<?php
namespace GBrabyn\DynamicForms\Transform;

/**
 * Used to turn a closure/anonymous function into a TransformAbstract instance
 *
 * @author GBrabyn
 */
class AnonymousFunctionTransform extends TransformAbstract
{
    /**
     *
     * @var \Closure 
     */
    protected $anonymousFunction;
    

    public function __construct(\Closure $anonymousFunction)
    {
        $this->anonymousFunction = $anonymousFunction;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getValue()
    {
        $func = $this->anonymousFunction;
        
        return $func($this->value);
    }
    
}
