<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use GBrabyn\DynamicForms\Error;

/**
 * Allows a callable function/method to be used for field validation
 *
 * @author GBrabyn
 */
class Callback extends FieldValidatorAbstract 
{
    /**
     *
     * @var Callable
     */
    private $callable;

    /**
     *
     * @var Error
     */
    private $error;

    /**
     * @var bool
     */
    private $useWhenEmpty = false;

    /**
     * @param callable $callable - takes args: mixed fieldValue [, Error error]
     * @param Error $error
     * @param array $options
     */
    public function __construct(Callable $callable, Error $error, array $options=[])
    {
        $this->callable = $callable;
        $this->error = $error;
        $this->setOptions($options);
    }


    private function setOptions(array $options)
    {
        if(\array_key_exists('useWhenEmpty', $options) && \is_bool($options['useWhenEmpty'])){
            $this->useWhenEmpty = $options['useWhenEmpty'];
        }
    }

    /**
     * Should the validator be used when the field value is null or empty string.
     *
     * @return boolean
     */
    public function useWhenEmpty() : bool
    {
        return $this->useWhenEmpty;
    }

    /**
     *
     * @return bool
     */
    public function isValid() : bool
    {
        $callable = $this->callable;

        return $callable($this->value, $this->error);
    }

    /**
     *
     * @return Error
     */
    public function getError() : Error
    {
        return $this->error;
    }
}
