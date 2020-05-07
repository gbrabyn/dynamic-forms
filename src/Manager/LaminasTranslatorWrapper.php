<?php


namespace GBrabyn\DynamicForms\Manager;

use GBrabyn\DynamicForms\TranslatorInterface as DynamicFormsTranslatorInterface;
use Laminas\Validator\Translator\TranslatorInterface as LaminasTranslatorInterface;

class LaminasTranslatorWrapper implements LaminasTranslatorInterface
{
    /**
     * @var DynamicFormsTranslatorInterface
     */
    private $translator;

    public function __construct(DynamicFormsTranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @return string
     */
    public function translate($message, $textDomain = 'default', $locale = null)
    {
        return $this->translator->translate($message);
    }
}