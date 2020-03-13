<?php
namespace GBrabyn\DynamicForms\Manager;

use GBrabyn\DynamicForms\Transform\LaminasFilterWrapper;
use Laminas\Filter\StripTags;

/**
 *
 * @author GBrabyn
 */
trait LaminasTransformTraits
{
    /**
     * Sets the filter options
     * Allowed options are
     *     'allowTags'     => Tags which are allowed
     *     'allowAttribs'  => Attributes which are allowed
     *     'allowComments' => Are comments allowed ?
     * 
     * @param  string|array|Traversable $options
     * @return LaminasFilterWrapper
     */
    public function stripTags($options=[])
    {
        $laminasFilter = new StripTags($options);
        
        return new LaminasFilterWrapper($laminasFilter);
    }
}
