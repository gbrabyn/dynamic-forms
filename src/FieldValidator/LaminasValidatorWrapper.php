<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;
use \Laminas\Validator\ValidatorInterface;

/**
 * Wrapper class to use Laminas Validators for Field Validators
 *
 * @author GBrabyn
 */
class LaminasValidatorWrapper extends FieldValidatorAbstract
{
    /**
     *
     * @var ValidatorInterface 
     */
    private $validator;
    
    /**
     *
     * @var Error 
     */
    private $error;
    
    /**
     * 
     * @param \Laminas\Validator\ValidatorInterface $laminasValidator
     * @param \GBrabyn\DynamicForms\Error $error
     */
    public function __construct(ValidatorInterface $laminasValidator, Error $error=null)
    {
        $this->validator = $laminasValidator;
        $this->error = $error;
    }
    
    /**
     * 
     * @return bool
     */
    public function isValid()
    {
        return $this->validator->isValid($this->value);
    }
    
    /**
     * 
     * @return Error
     */
    public function getError()
    {
        if($this->error){
            return $this->error;
        }
        
        $messages = $this->validator->getMessages();
        $firstKey = key($messages);

        return new Error($messages[$firstKey]);
    }
}
