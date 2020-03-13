<?php


namespace GBrabyn\DynamicForms\Component;

trait ComponentUserTrait
{
    private function getComponents() : array
    {
        $ret = [];
        foreach($this as $name => $property){
            if($name !== 'parent' && $property instanceof ComponentAbstract){
                $ret[] = $property;
            }
        }

        return $ret;
    }

    public function getComponent(string $propertyName) : ComponentAbstract
    {
        if(\property_exists($this, $propertyName) && $this->$propertyName instanceof ComponentAbstract){
            return $this->$propertyName;
        }

        throw new \InvalidArgumentException('No property with name "'.$propertyName.'" exists in '.static::class.' that holds '.ComponentAbstract::class);
    }

    public function activateComponents()
    {
        foreach($this->getComponents() AS $component){
            /** @var $component ComponentAbstract **/
            $component->setParent($this);
            $component->setValidatorManager($this->validators);
            $component->setTransformManager($this->transformers);
            $component->doActivate();
        }
    }

}