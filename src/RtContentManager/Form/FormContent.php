<?php

namespace RtContentManager\Form;
 
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
 
class FormContent extends Form {
    
    public function __construct($cnfg, $entityManager) {
 
        parent::__construct('form');
        $this->setAttribute('action', '');
        $this->setAttribute('method', 'post');
        $this->setInputFilter($this->getFilters());
        
        //Язык
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'lang',
            'required' => true,
            'options' => array(
                'label' => 'Language',
                'object_manager' => $entityManager,
                'target_class'   => $cnfg['content_entity']['lang'],
                'property'       => 'name',
                'empty_option'   => '',
                'is_method'      => true,                
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy'  => array('name' => 'ASC'),
                    ),
                ),
            ),            
        ));
        
        //Тип контента
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'contentType',
            'required' => true,
            'options' => array(
                'label' => 'Content type',
                'object_manager' => $entityManager,
                'target_class'   => $cnfg['content_entity']['contentType'],
                'property'       => 'name',
                'empty_option' => '',
                'is_method'      => true,                
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy'  => array('name' => 'ASC'),
                    ),
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'size'  => '128',
        )));
        
        $this->add(array(            
            'name' => 'valuesh',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Content (partial)',
            ),
            'attributes' => array(
                'cols'  => '80',
                'rows'  => '2',
                'id'    => 'valuesh',
                //'style' => 'width:550px;height:180px;',
        )));
        
        $this->add(array(            
            'name' => 'valuefl',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Content (full)',
            ),
            'attributes' => array(
                'cols'  => '80',
                'rows'  => '2',
                'id'    => 'valuefl',
                //'style' => 'width:550px;height:180px;',
        )));
        
        $date=new \DateTime(); //this returns the current date time
        $now = $date->format('Y-m-d H:i');
        
        $this->add(array(
             'type' => 'datetime',
             'name' => 'dt',
             'value' => $now,
             'options' => array(
                 'label' => 'Publication date',
                 'format' => 'Y-m-d H:i',
             ),
             'attributes' => array(
                 'step' => '1', // days; default step interval is 1 day
                 'value' => $now,
        )));
        
        //link
        $this->add(array(
            'name' => 'link',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'link',
            )
        ));
    }
        
    protected function getFilters(){
        $inputFilter = new InputFilter();
        $factory = new InputFactory();
        
        //name
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
                        'max' => 255,
                    ),
                ),
            ),
        )));
        
        return $inputFilter;
        }       
    }