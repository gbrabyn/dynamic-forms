<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/26/18
 * Time: 10:14 AM
 */

namespace GBrabyn\DynamicForms\Manager;

use GBrabyn\DynamicForms\Element\EscaperInterface;

class ElementManager extends ElementManagerAbstract
{
    /**
     *
     * @param string $doctype
     * @param EscaperInterface $escaper
     * @param string $locale
     */
    public function __construct(string $doctype, EscaperInterface $escaper, $locale=null)
    {
        $this->setDocType($doctype);
        $this->setEscaper($escaper);
        $this->setLocale($locale);
    }

    /**
     *
     * @param EscaperInterface $escaper
     */
    protected function setEscaper($escaper)
    {
        $this->escaper = $escaper;
    }

    /**
     *
     * @param string $locale
     */
    protected function setLocale($locale)
    {
        if($locale){
            $this->locale = $locale;
        }
    }
}