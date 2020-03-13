<?php
/**
 * Created by PhpStorm.
 * User: GBrabyn
 * Date: 11/23/18
 * Time: 11:30 AM
 */

namespace GBrabyn\DynamicForms;

use GBrabyn\DynamicForms\Component\ComponentUserInterface;
use GBrabyn\DynamicForms\Component\ComponentUserTrait;
use GBrabyn\DynamicForms\Element\EscaperInterface;
use GBrabyn\DynamicForms\Element\LaminasEscaperWrapper;
use GBrabyn\DynamicForms\Manager\ElementManager;
use GBrabyn\DynamicForms\Manager\ElementManagerAbstract;
use GBrabyn\DynamicForms\Manager\ErrorDecoratorManager;
use GBrabyn\DynamicForms\Manager\TransformManager;
use GBrabyn\DynamicForms\Manager\ValidatorManager;
use GBrabyn\DynamicForms\ViewWrapper\ViewWrapperAbstract;
use Laminas\Escaper\Escaper;

abstract class FormUsingViewWrapperAbstract extends FormAbstract
    implements ViewWrapper\ViewWrapperUserInterface, \SplObserver, ComponentUserInterface
{
    use ViewWrapper\GetOptionsTrait, ComponentUserTrait;

    protected $charset = 'utf-8';

    protected $locale = 'en_US';

    protected $doctype = 'html5';

    /* @var EscaperInterface */
    protected $escaper;

    /* @var TranslatorInterface */
    protected $translator;

    /* @var ElementManagerAbstract */
    protected $elements;

    /* @var ErrorDecoratorManager */
    protected $errorDecorators;

    /* @var ValidatorManager */
    protected $validators;

    /* @var TransformManager */
    protected $transformers;

    /**
     * Whether the _activate() method has been called yet.
     * @var bool
     */
    private $activated = false;


    public function __construct(?TranslatorInterface $translator =  null)
    {
        $this->translator = $translator;
        $this->attach($this);
    }

    /**
     * Intended to be used on individual forms and holds code that needs to run before the form is populated or displayed.
     * Example of use is to set the Options used in the Form.
     *
     * @return void
     */
    abstract protected function activate();

    /**
     * Intended to hold settings that are used project wide and should be implemented in a parent class. The method is
     * run before the form is populated or displayed.
     * Examples of use would be to register error decorators that are available on all instances and to add universal
     * transformers for all instances.
     *
     * @return void
     */
    abstract protected function baseActivate();

    /**
     * Makes Form usuable. Is called before the Form is populated or displayed
     */
    private function _activate()
    {
        if ($this->activated === true) {
            return;
        }

        $this->setEscaper();
        $this->setElementManager();
        $this->setErrorDecoratorManager();
        $this->setTransformerManager();
        $this->setValidatorManager();

        $this->baseActivate();
        $this->activate();
        $this->activateComponents();
        $this->activated = true;
    }

    /**
     *
     * @param \SplSubject $subject
     * @param string $event
     */
    public function update(\SplSubject $subject, $event = null)
    {
        if ($event === 'populateStart') {
            $this->_activate();
        }
    }


    abstract protected function getViewWrapper() : ViewWrapperAbstract;


    public function viewWrapper() : ViewWrapperAbstract
    {
        $this->_activate();

        return $this->getViewWrapper();
    }


    public function setEscaper(?EscaperInterface $escaper=null)
    {
        if($escaper){
            $this->escaper = $escaper;
        }elseif($this->escaper === null){
            $this->escaper = new LaminasEscaperWrapper(new Escaper($this->charset));
        }
    }


    public function setElementManager(ElementManagerAbstract $elementManager=null)
    {
        if($elementManager){
            $this->elements = $elementManager;
        }elseif($this->elements === null){
            $this->elements = $this->getDefaultElementManager();
        }
    }


    protected function getDefaultElementManager() : ElementManagerAbstract
    {
        return new ElementManager($this->doctype, $this->escaper, $this->locale);
    }


    public function setErrorDecoratorManager($errorDecoratorManager=null)
    {
        if($errorDecoratorManager){
            $this->errorDecorators = $errorDecoratorManager;
        }elseif($this->errorDecorators === null){
            $this->errorDecorators = $this->getDefaultErrorDecoratorManager();
        }
    }


    protected function getDefaultErrorDecoratorManager()
    {
        return new ErrorDecoratorManager($this->doctype, $this->translator);
    }


    public function setTransformerManager($transformManager=null)
    {
        if($transformManager){
            $this->transformers = $transformManager;
        }elseif($this->transformers === null){
            $this->transformers = $this->getDefaultTransformerManager();
        }
    }


    protected function getDefaultTransformerManager()
    {
        return new TransformManager();
    }


    public function setValidatorManager($validatorManager=null)
    {
        if($validatorManager){
            $this->validators = $validatorManager;
        }elseif($this->validators === null){
            $this->validators = $this->getDefaultValidatorManager();
        }
    }

    protected function getDefaultValidatorManager()
    {
        return new ValidatorManager();
    }

}