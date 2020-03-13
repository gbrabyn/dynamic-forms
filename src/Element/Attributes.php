<?php
namespace GBrabyn\DynamicForms\Element;

use GBrabyn\DynamicForms\Exception\DynamicFormsException;

/**
 * Holds and renders element attributes
 *
 * @author GBrabyn
 */
class Attributes 
{
    /**
     *
     * @var array - come in name=>value pairs
     */
    private $attributePairs = [];
    
    /**
     *
     * @var array - not in pairs, i.e. ['attr1', 'attr2']. Used when attribute should NOT
     *              display in name=>value pairs.
     */
    private $attributesSingle = [];
    
    
    private $classes = [];
    
    /**
     *
     * @var EscaperInterface 
     */
    private $escaper;
    
    /**
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes=[])
    {
        $this->add($attributes);
    }
    
    /**
     * 
     * @param array $attributes
     */
    public function add(array $attributes){
        foreach($attributes AS $name => $value){
            if($name === 'class'){
                $this->addToClass($value);
            }elseif(\is_int($name)){
                $this->addToAttributesSingle(\strtolower($value));
            }else{
                $this->attributePairs[\strtolower($name)] = $value;
            }
        }
    }
    
    /**
     * 
     * @param string $classStr
     */
    private function addToClass($classStr)
    {
        $parts = \explode(' ', $classStr);
        foreach($parts AS $class){
            if(!\in_array($class, $this->classes)){
                $this->classes[] = $class;
            }
        }
    }
    
    /**
     * 
     * @param string $attribute
     */
    private function addToAttributesSingle($attribute)
    {
        if(!\in_array($attribute, $this->attributesSingle) && $attribute !== null){
            $this->attributesSingle[] = $attribute;
        } 
    }

    /**
     * 
     * @param \GBrabyn\DynamicForms\Element\EscaperInterface $escaper
     */
    public function setEscaper(EscaperInterface $escaper)
    {
        $this->escaper = $escaper;
    }
    
    /**
     * 
     * @param string $string
     * @return string
     */
    private function escapeAttr($string)
    {
        if(!$this->escaper){
            throw new DynamicFormsException(__METHOD__.' called without setEscaper() being used.');
        }
        
        return $this->escaper->escapeAttr((string)$string);
    }
    
    /**
     * Does an attribute of $name exist
     * 
     * @param string $name
     * @return bool
     */
    public function has(string $name)
    {
        if($name === 'class'){
            return \count($this->classes) > 0;
        }
        
        return \array_key_exists($name, $this->attributePairs) || \in_array($name, $this->attributesSingle);
    }
    
    /**
     * Does an attribute of $name exist and is it assigned a value that is not null or empty string
     * 
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name)
    {
        if($name === 'class'){
            return \count($this->classes) > 0;
        }
        
        return \array_key_exists($name, $this->attributePairs) && !\in_array($this->attributePairs[$name], [null, ''], true);
    }
    
    /**
     * Get the string value for an attribute
     * 
     * @param string $name
     * @return string
     */
    public function get($name)
    {
        if($name === 'class'){
            return \implode(' ', $this->classes);
        }
        
        return $this->attributePairs[$name];
    }


    public function remove(string $name)
    {
        if(\array_key_exists($name, $this->attributePairs)){
            unset($this->attributePairs[$name]);
        }

        $key = \array_search($name, $this->attributesSingle);
        if($key !== false){
            unset($this->attributesSingle[$key]);
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getAsString()
    {
        $strArr = [];

        foreach($this->attributePairs AS $name => $val){
            if($val === null && false === \in_array($name, ['value'])){
                continue;
            }

            $strArr[] = $name.'="'.$this->escapeAttr($val).'"';
        }
        
        $escapedClasses = [];
        foreach($this->classes AS $class){
            $escapedClasses[] = $this->escapeAttr($class);
        }
        
        if(count($this->classes) > 0){
            $strArr[] = 'class="'.\implode(' ', $escapedClasses).'"';
        }
        
        foreach($this->attributesSingle AS $attr){
            $strArr[] = $this->escapeAttr($attr);
        }

        return \implode(' ', $strArr);
    }
    
}
