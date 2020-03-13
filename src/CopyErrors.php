<?php
namespace GBrabyn\DynamicForms;

/**
 * Copies any errors to another field. Useful, for example, with suggesters/javascript 
 * that provide a value for a hidden field that carries the submitted data.
 *
 * @author GBrabyn
 */
class CopyErrors implements \SplObserver 
{
    /**
     *
     * @var Field
     */
    private $from, $to;
    

    public function __construct(Field $from, Field $to)
    {
        $this->from = $from;
        $this->to = $to;
        $this->from->attach($this);
    }
    
    /**
     * 
     * @param \SplSubject $subject
     * @param string $event
     */
    public function update(\SplSubject $subject, $event=null)
    {
        if($event === 'errorAdded'){
            $this->transferErrors();
        }
    }

    private function transferErrors()
    {
        $errors = $this->from->getErrors();
        $this->to->addError(\end($errors));
    }
}
