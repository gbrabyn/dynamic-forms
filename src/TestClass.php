<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GBrabyn\DynamicForms;

/**
 * Description of TestClass
 *
 * @author gregor.brabyn 
 */
class TestClass {
    //put your code here
    
    /** @var int **/
    private $form;
    
    private $bom;
    
    /**
     * 
     * @param type $id
     * @return int
     */
    public function test($id) : int {
        return (int)$id;
    }
    
    public function do() : int
    {
        return $this->test('234');
    }
}
