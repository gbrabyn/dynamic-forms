<?php
namespace GBrabyn\DynamicForms;

use \GBrabyn\DynamicForms\Exception\DynamicFormsException;

/**
 * Description of Error
 *
 * @author GBrabyn
 */
class Message 
{
    
    private $message;
    
    private $translationKey;
    
    private $args;
    
    /**
     * 
     * @param string $message
     * @param string $translationKey
     * @param array $args - associative, Used for replacing dynamic content in the message
     */
    public function __construct($message, $translationKey=null, array $args=[])
    {
        $this->message = $message;
        $this->translationKey = $translationKey;
        $this->setArgs($args);
    }
    
    /**
     * Used for replacing dynamic content in the message.
     * 
     * Example: a message "Must be between {min} and {max} characters length" 
     * would need $args to be something like ['{min}'=>2, '{max}'=>3]
     * 
     * @param array $args - placeHolder=>replacement pairs
     */
    public function setArgs(array $args)
    {
        if(false === $this->isAssociative($args)){
            throw new DynamicFormsException(__METHOD__.' called with $args not being an associative array of placeHolder=>replacement pairs.');
        }
        
        $this->args = $args;
    }
    
    /**
     * 
     * @return string
     */
    public function getMessage() 
    {
        return $this->message;
    }

    /**
     * 
     * @return string
     */
    public function getTranslationKey() 
    {
        return $this->translationKey;
    }

    /**
     * 
     * @return array - placeHolder=>replacement pairs
     */
    public function getArgs() 
    {
        return $this->args;
    }
    
    /**
     * 
     * @param array $array
     * @return boolean
     */
    private function isAssociative(array $array)
    {
	foreach(\array_keys($array) as $key){
            if (\is_int($key)){
                return false;
            }
        }
        
	return true;
    }

}
