<?php

namespace GBrabyn\DynamicForms\Element;

/**
 * Enables "Laminas" Escaper to be used as the Element Escaper
 *
 * @author GBrabyn
 */
class LaminasEscaperWrapper implements EscaperInterface
{

    private $escaper;
    
    
    public function __construct(\Laminas\Escaper\Escaper $escaper)
    {
        $this->escaper = $escaper;
    }
    
    
    /**
     * 
     * @param string $string
     * @return string
     */
    public function escapeAttr($string)
    {
        return $this->escaper->escapeHtmlAttr($string);
    }
    
    /**
     * 
     * @param string $string
     * @return string
     */
    public function escapeHtml($string)
    {
        return $this->escaper->escapeHtml($string);
    }

}
