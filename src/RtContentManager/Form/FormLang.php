<?php

namespace RtContentManager\Form;
 
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
 
class FormLang extends Form {
 
    public function __construct($action='') {
 
        parent::__construct('page');
        $this->setAttribute('action', $action);
        $this->setAttribute('method', 'post');
        $this->setInputFilter($this->getFilters());
        
        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'size'  => '2',
                'id' => 'name',
        )));
    }
        
    protected function getFilters(){
        $inputFilter = new InputFilter();
        $factory = new InputFactory();
              
          
        $inputFilter->add($factory->createInput(array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 2)
                        )  
                )
            )));        
        
        return $inputFilter;
        }       
    }