<?php

namespace RtContentManager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LangController extends AbstractActionController
{
    
    private $linkPreviousPage;
    private $filterForm;
    private $entityManager;//
    private $classLoader;//
    private $zfcAuth;
    private $translate;
    private $cnfg;//
    
    public function __construct($linkPreviousPage, $filterForm, $entityManager, 
            $classLoader, $zfcAuth, $translate, $cnfg)
    {
        $this->linkPreviousPage = $linkPreviousPage;
        $this->filterForm = $filterForm;
        $this->entityManager = $entityManager;
        $this->classLoader = $classLoader;
        $this->zfcAuth = $zfcAuth;
        $this->translate = $translate;
        $this->cnfg = $cnfg;
    }
    public function indexAction()
    {
        $query=$this->entityManager->createQuery(
            'SELECT l FROM '.$this->cnfg['content_entity']['lang'].' l
            ORDER BY l.name');
        $data=$query->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
        
        return new ViewModel(array(
            'paginator' => $data));
    }

    public function addAction()
    {
        $form =new \RtContentManager\Form\FormLang();
        $form->setHydrator(new \Zend\Stdlib\Hydrator\Reflection());
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $ent=$this->classLoader->getClass($this->cnfg['content_entity']['lang']);
                $ent->exchangeArray($form->getData());
                $this->entityManager->persist($ent);
                $this->entityManager->flush();
                
                return $this->redirect()->toRoute('contentmanager/lang',
                    array('lang' => $this->params()->fromRoute('lang', 'ru')));
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
        $form =new \RtContentManager\Form\FormLang();
        $form->setHydrator(new \Zend\Stdlib\Hydrator\Reflection());
        
        //Ищем запись и, если запись не найдена
        $ent = $this->entityManager->
                getRepository($this->cnfg['content_entity']['lang'])->
                find((int)$this->params()->fromRoute('id', 0));
        if ($ent==NUll)
            return $this->redirect()->toRoute('contentmanager/lang',
                    array('lang' => $this->params()->fromRoute('lang', 'ru')));
        
        //Работаем с возвращенными из формы данными
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $ent->exchangeArray($form->getData());
                $this->entityManager->persist($ent);
                $this->entityManager->flush();
                
                return $this->redirect()->toRoute('contentmanager/lang',
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

    public function deleteAction()
    {
        $ent = $this->entityManager->
                getRepository($this->cnfg['content_entity']['lang'])->
                find((int)$this->params()->fromRoute('id', 0));
        //Если запись найдена
        if ($ent!=NUll) {                   
            $this->entityManager->remove($ent);
            $this->entityManager->flush();
        }        
        //Возвращаемся
        return $this->redirect()->toRoute('contentmanager/lang',
            array('lang' => $this->params()->fromRoute('lang', 'ru')));
    }
}