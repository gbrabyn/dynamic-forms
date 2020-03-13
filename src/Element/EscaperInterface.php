<?php

namespace GBrabyn\DynamicForms\Element;

/**
 *
 * @author GBrabyn
 */
interface EscaperInterface 
{
    /**
     * 
     * @param string $string
     * @return string
     */
    public function escapeAttr($string);
    
    /**
     * 
     * @param string $string
     * @return string
     */
    public function escapeHtml($string);
    
}
