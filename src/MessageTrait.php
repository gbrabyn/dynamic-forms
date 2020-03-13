<?php
namespace GBrabyn\DynamicForms;

/**
 *
 * @author GBrabyn
 */
trait MessageTrait 
{
    /**
     * Converts messages to strings
     * 
     * @param Message[] $messages
     * @param int|null $max
     * @return string[]
     */
    protected function messages(array $messages, $max=null)
    {
        $countAll = \count($messages);
        $maxMessages = ($max===null || $countAll < $max) ? $countAll : $max;

        for($ret=[], $x=0; $x < $maxMessages; $x++){
            $ret[] = $this->getMessage($messages, $x);
        }
        
        return $ret;
    }

    /**
     * 
     * @param Message[] $messages
     * @param int $index
     * @return string
     */
    protected function getMessage(array $messages, $index)
    {
        $this->messageArgsCheck($messages, $index);
        
        $message = $messages[$index];

        $ret = null;
        if($this->hasTranslator()){
            $ret = $this->translate($message->getTranslationKey());
        }
        
        if($ret === null || $ret === $message->getTranslationKey()){
            $ret = $message->getMessage();
        }
        
        return \strtr($ret, $message->getArgs());
    }
    
    /**
     * 
     * @param Message[] $messages
     * @param int $index
     * @throws \InvalidArgumentException
     */
    private function messageArgsCheck(array $messages, $index)
    {
        if(!\array_key_exists($index, $messages)){
            throw new \InvalidArgumentException(__METHOD__.' called with an invalid $index of: '.$index);
        }
        
        if(! $messages[$index] instanceof Message){
            throw new \InvalidArgumentException(__METHOD__.' called with $messages array not containing Message objects');
        }
    }
}
