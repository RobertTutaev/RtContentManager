<?php

namespace RtContentManager\Form;
 
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
 
class FormContentType extends Form {
 
    public function __construct($cnfg, $action='') {
 
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
                'size'  => '128',
                'id'    => 'name',
        )));
        
        $this->add(array(
            'name' => 'tagOpen',
            'type' => 'text',
            'options' => array(
                'label' => 'Opening tag',
            ),
            'attributes' => array(
                'size'  => '64',
                'id'    => 'tagOpen',
                'value' => '<h4>',
        )));
        
        $this->add(array(
            'name' => 'tagClose',
            'type' => 'text',
            'options' => array(
                'label' => 'Closing tag',
            ),
            'attributes' => array(
                'size'  => '64',
                'id'    => 'tagClose',
                'value' => '</h4>',
        )));
        
        $this->add(array(
            'name' => 'displayType',
            'type' => 'select',
            'options' => array(
                'label' => "Display type",
                'value_options' => $cnfg['content_display_type'],
            ),
            'attributes' => array(
                'value' => '1',
            )
        ));
        
        $this->add(array(
            'name' => 'displayDt',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Display date',
                'id' => 'displayDt'
            ),
            'attributes' => array(
                'checked' => False,
        )));
        
        $this->add(array(
            'name' => 'displayUser',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Display user',
                'id' => 'displayUser'
            ),
            'attributes' => array(
                'checked' => False,
        )));
        
        $this->add(array(
            'name' => 'linkType',
            'type' => 'select',
            'options' => array(
                'label' => "Link type",
                'value_options' => $cnfg['content_link_type'],
            ),
            'attributes' => array(
                'value' => '1',
            )
        ));
        
        $this->add(array(
            'name' => 'openNewPage',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Open a new page',
                'id' => 'openNewPage'
            ),
            'attributes' => array(
                'checked' => False,
        )));
        
        $this->add(array(
            'name' => 'orderField',
            'type' => 'select',
            'options' => array(
                'label' => 'Order by',
                'value_options' => $cnfg['content_order_field'],
            ),
            'attributes' => array(
                'value' => '1',
            )
        ));
        
        $this->add(array(
            'name' => 'orderDesc',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Descending',
                'id' => 'orderDesc'
            ),
            'attributes' => array(
                'checked' => False,
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
                            'min' => 1,
                            'max' => 255)
                        )
                )
        )));
        
        $inputFilter->add($factory->createInput(array(
                'name' => 'tagOpen',
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 255)
                        )
                )
        )));
        
        $inputFilter->add($factory->createInput(array(
                'name' => 'tagClose',
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 255)
                        )
                )
        )));
        
        return $inputFilter;
        }       
    }