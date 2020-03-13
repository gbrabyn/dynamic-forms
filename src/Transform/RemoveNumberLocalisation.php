<?php
namespace GBrabyn\DynamicForms\Transform;

/**
 * Takes a number with a localised format and turns it into a standard number for use in script
 *
 * @author GBrabyn
 */
class RemoveNumberLocalisation extends TransformAbstract 
{
    private $locale;

    
    public function __construct($locale)
    {
        $this->locale = $locale;
    }
    
    /**
     * 
     * @return int|float
     */
    public function getValue()
    {
        $fmt = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL );
        
        $val = $fmt->parse($this->value);
        
        if($val === false){
            return $this->value;
        }
        
        return $val;
    }
}
