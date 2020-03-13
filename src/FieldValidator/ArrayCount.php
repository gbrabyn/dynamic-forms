<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/9/18
 * Time: 3:19 PM
 */

namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

class ArrayCount extends FieldValidatorAbstract
{
    const NOT_ARRAY = 'inputCorrupted';
    const NOT_BETWEEN = 'numItemsNotBetween';
    const LESS_THAN = 'numItemsLessThan';
    const MORE_THAN = 'numItemsGreaterThan';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_ARRAY => "Input is corrupted",
        self::NOT_BETWEEN => 'Number of items must be between ${min} and ${max}',
        self::LESS_THAN => 'Must be ${min} or more items',
        self::MORE_THAN => 'Maximum of ${max} items',
    ];

    /**
     *
     * @var Error
     */
    private $error;

    private $min;
    private $max;
    private $messageType;

    /**
     * ArrayCount constructor.
     * @param int $min
     * @param int|null $max
     * @param Error|null $error
     */
    public function __construct($min=0, $max=null, Error $error=null)
    {
        $this->min = $min;
        $this->max = $max;
        $this->error = $error;
    }

    /**
     * Should the validator be used when the field value is null or empty string
     *
     * @return boolean
     */
    public function useWhenEmpty()
    {
        return true;
    }

    /**
     *
     * @return bool
     */
    public function isValid()
    {
        if($this->value === null){
            $this->value = [];
        }

        if(false === \is_array($this->value)){
            $this->messageType = self::NOT_ARRAY;
            return false;
        }

        $numItems = \count($this->value);

        if($this->min > 0 && $this->max !== null){
            $this->messageType = self::NOT_BETWEEN;

            return $numItems >= $this->min && $numItems <= $this->max;
        }

        if($this->max !== null){
            $this->messageType = self::MORE_THAN;

            return $numItems <= $this->max;
        }

        $this->messageType = self::LESS_THAN;

        return $numItems >= $this->min;
    }

    /**
     *
     * @return Error
     */
    public function getError()
    {
        if($this->error){
            $this->error->setArgs($this->getMessageArgs());
            return $this->error;
        }

        return new Error($this->messageTemplates[$this->messageType], $this->messageType, $this->getMessageArgs());
    }


    private function getMessageArgs()
    {
        return ['${min}'=>$this->min, '${max}'=>$this->max];
    }
}