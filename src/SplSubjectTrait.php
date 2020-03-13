<?php
namespace GBrabyn\DynamicForms;

/**
 *
 * @author GBrabyn
 */
trait SplSubjectTrait
{
    /**
     * List of objects monitoring for change of state
     *
     * @var array
     */
    protected $observers = [];
    
    /**
     * Add observer (from observer design pattern)
     * 
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer)
    {
        $i = \array_search($observer, $this->observers);
        if ($i === false) {
            $this->observers[] = $observer;
        }
    }

    /**
     * Remove observer (from observer design pattern)
     * 
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        $key = \array_search($observer,$this->observers, true);
        if($key){
            unset($this->observers[$key]);
        }
    }
    
    /**
     * 
     * @param string $event
     */
    public function notify($event=null)
    {
        foreach($this->observers as $value){
            $value->update($this, $event);
        }
    }
}
