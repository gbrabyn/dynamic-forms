<?php
namespace GBrabyn\DynamicForms\Transform;

use \Laminas\Filter\FilterInterface;

/**
 * Wrapper class to use Laminas Filters as transformers
 *
 * @author GBrabyn
 */
class LaminasFilterWrapper extends TransformAbstract
{
    /**
     *
     * @var \Laminas\Filter\FilterInterface
     */
    private $filter;
    
    /**
     * 
     * @param \Laminas\Filter\FilterInterface $laminasFilter
     */
    public function __construct(FilterInterface $laminasFilter)
    {
        $this->filter = $laminasFilter;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->filter->filter($this->value);
    }
}
