<?php
namespace GBrabyn\DynamicForms\GroupValidator;

use GBrabyn\DynamicForms\Error;

/**
 * Allows a callable function/method to be used for group validation
 *
 * @author GBrabyn
 */
class Callback implements GroupValidatorInterface
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
     * @var array
     */
    private $args;

    /**
     * @param callable $callable - takes args: array $args [, Error error]
     * @param Error $error
     */
    public function __construct(Callable $callable, Error $error, array $args=[])
    {
        $this->callable = $callable;
        $this->error = $error;
        $this->args = $args;
    }

    /**
     *
     * @return bool
     */
    public function isValid() : bool
    {
        $callable = $this->callable;

        return $callable($this->args, $this->error);
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
