<?php

namespace RtContentManager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class ContentTypeController extends AbstractActionController
{
    
    private $filterForm;
    private $entityManager;
    private $classLoader;
    private $translate;
    private $cnfg;//

    public function __construct($filterForm, $entityManager, $classLoader, 
            $translate, $cnfg)
    {
        $this->filterForm = $filterForm;
        $this->entityManager = $entityManager;
        $this->classLoader = $classLoader;
        $this->translate = $translate;
        $this->cnfg = $cnfg;
    }
    
    public function indexAction()
    {
        $this->filterForm->setFilter('contentType');
        if ($this->getRequest()->isPost()){
            $this->filterForm->setData($this->getRequest()->getPost());
            if ($this->filterForm->isValid()){
                $v=$this->filterForm->getData();
            }
        }else
            $v=$this->filterForm->getDataDefault();
        
        $query=$this->entityManager->createQuery(
            'SELECT t FROM '.$this->cnfg['content_entity']['contentType'].' t
            WHERE '.$v['f_search'].' like :value
            ORDER BY '.$v['f_sort'].$v['f_desc'])->
                setParameter('value', $v['f_value']);
        
        // Create the paginator itself
        $paginator= new Paginator(new DoctrinePaginator(new ORMPaginator($query)));
        $paginator->setDefaultItemCountPerPage($v['f_limit']);        
        $paginator->setCurrentPageNumber((int)$this->params()->fromRoute('page', 1));
        
        return new ViewModel(array(
            'paginator' => $paginator, 
            'form'      => $this->filterForm));
    }

    public function addAction()
    {
        $form =new \RtContentManager\Form\FormContentType($this->cnfg);
        $form->setHydrator(new \Zend\Stdlib\Hydrator\Reflection());
                
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $ent=$this->classLoader->getClass($this->cnfg['content_entity']['contentType']);
                $ent->exchangeArray($form->getData());
                $this->entityManager->persist($ent);
                $this->entityManager->flush();
                
                return $this->redirect()->toRoute('contentmanager/contenttype', array(
                    'lang' => $this->params()->fromRoute('lang', 'ru')));
            } else {
                return new ViewModel(array(
                    'form' => $form));
            }
        } else
            return new ViewModel(array(
                'form' => $form));
    }
    
    public function editAction()
    {
        $form =new \RtContentManager\Form\FormContentType($this->cnfg);
        $form->setHydrator(new \Zend\Stdlib\Hydrator\Reflection());
        
        //Ищем запись и, если запись не найдена
        $ent = $this->entityManager->
                getRepository($this->cnfg['content_entity']['contentType'])->
                find((int)$this->params()->fromRoute('id', 0));
        if ($ent==NUll)
            return $this->redirect()->toRoute('contentmanager/contenttype', array(
                'lang' => $this->params()->fromRoute('lang', 'ru')));
        
        //Работаем с возвращенными из формы данными
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $ent->exchangeArray($form->getData());
                $this->entityManager->persist($ent);
                $this->entityManager->flush();
                
                return $this->redirect()->toRoute('contentmanager/contenttype',
                    array('lang' => $this->params()->fromRoute('lang', 'ru')));
            } else {
                return new ViewModel(array(
                    'form' => $form));
            }
        } else {
            //Наполняем форму данными при первом запуске
            $form->bind($ent);
            return new ViewModel(array(
                'form' => $form));
        }
    }
    
    public function copyAction()
    {
        //1. Инициализация
        $form =new \RtContentManager\Form\FormContentType($this->cnfg);
        $form ->setHydrator(new \Zend\Stdlib\Hydrator\Reflection());
        
        //2. Если данные отправлены и проверены
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {                
                $ent=$this->classLoader->getClass($this->cnfg['content_entity']['contentType']);
                $ent->exchangeArray($form->getData());
                $this->entityManager->persist($ent);
                $this->entityManager->flush();
                
                return $this->redirect()->toRoute('contentmanager/contenttype',
                    array('lang' => $this->params()->fromRoute('lang', 'ru')));
            } else {
                return new ViewModel(array(
                    'form' => $form));
            }
        } else {
            //3. Если это первый запуск формы
            $entOld=$this->entityManager->
                    getRepository($this->cnfg['content_entity']['contentType'])->
                    find((int)$this->params()->fromRoute('id', 0));            
            
            if($entOld!=NULL){
                $ent=$this->classLoader->getClass($this->cnfg['content_entity']['contentType']);
                $ent->exchangeArray($entOld);
                
                $t=$this->translate;
                $ent->setName($ent->getName().' ('.$t('Copy').')');
                $form->bind($ent);
            }
            
            return new ViewModel(array(
                'form' => $form));
        }
    }
    
    public function deleteAction()
    {        
        //Ищем запись и, если запись найдена
        $ent = $this->entityManager->
                getRepository($this->cnfg['content_entity']['contentType'])->
                find((int)$this->params()->fromRoute('id', 0));
        if ($ent!=NUll) {            
            $this->entityManager->remove($ent);
            $this->entityManager->flush();
        }
        //Возвращаемся
        return $this->redirect()->toRoute('contentmanager/contenttype',
            array('lang' => $this->params()->fromRoute('lang', 'ru')));
    }
}