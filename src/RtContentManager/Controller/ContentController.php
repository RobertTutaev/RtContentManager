<?php

namespace RtContentManager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class ContentController extends AbstractActionController
{
    
    private $linkPreviousPage;
    private $filterForm;
    private $entityManager;
    private $classLoader;
    private $zfcAuth;
    private $translate;
    private $cnfg;

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
        $this->filterForm->setFilter('content');
        if ($this->getRequest()->isPost()){
            $this->filterForm->setData($this->getRequest()->getPost());
            if ($this->filterForm->isValid()){
                $v=$this->filterForm->getData();
            }
        }else
            $v=$this->filterForm->getDataDefault();
        
        $query=$this->entityManager->createQuery(
            'SELECT c FROM '.$this->cnfg['content_entity']['content'].' c
            JOIN c.contentType t
            JOIN c.lang l
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
        $form =new \RtContentManager\Form\FormContent($this->cnfg, $this->entityManager);
        $form ->setHydrator(new \Zend\Stdlib\Hydrator\Reflection());
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $ent=$this->classLoader->getClass($this->cnfg['content_entity']['content']);
                $ent->exchangeArray($form->getData());                
                
                $lang=$this->entityManager->
                    getRepository($this->cnfg['content_entity']['lang'])->find($ent->getLang());
                $ent->setLang($lang);
                $ContentType=$this->entityManager->
                    getRepository($this->cnfg['content_entity']['contentType'])->find($ent->getContentType());
                $ent->setContentType($ContentType);
                $ent->setDt(new \DateTime($ent->getDt()));                                
                $ent->setUser($this->zfcAuth->getIdentity());
                $this->entityManager->persist($ent);
                $this->entityManager->flush();
                
                return $this->redirect()->toRoute('contentmanager/content',
                    array(
                        'lang' => $this->params()->fromRoute('lang', 'ru')));
            } else {
                return new ViewModel(array(
                    'form' => $form));
            }
        } else
            return new ViewModel(array(
                'form' => $form));
    }
    
    public function copyAction()
    {
        //1. Инициализация
        $form =new \RtContentManager\Form\FormContent($this->cnfg, $this->entityManager);
        $form ->setHydrator(new \Zend\Stdlib\Hydrator\Reflection());
        
        //2. Если данные отправлены и проверены
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {                
                $ent=$this->classLoader->getClass($this->cnfg['content_entity']['content']);
                $ent->exchangeArray($form->getData());
                
                $lang=$this->entityManager->
                    getRepository($this->cnfg['content_entity']['lang'])->find($ent->getLang());
                $ent->setLang($lang);
                $ContentType=$this->entityManager->
                    getRepository($this->cnfg['content_entity']['contentType'])->find($ent->getContentType());
                $ent->setContentType($ContentType);
                $ent->setDt(new \DateTime($ent->getDt()));
                $ent->setUser($this->zfcAuth->getIdentity());
                $this->entityManager->persist($ent);
                $this->entityManager->flush();
                
                return $this->redirect()->toRoute('contentmanager/content',
                    array('lang' => $this->params()->fromRoute('lang', 'ru')));
            } else {
                return new ViewModel(array(
                    'form' => $form));
            }
        } else {
            //3. Если это первый запуск формы
            $entOld=$this->entityManager->
                    getRepository($this->cnfg['content_entity']['content'])->
                    find((int)$this->params()->fromRoute('id', 0));            
            
            if($entOld!=NULL){
                $ent=$this->classLoader->getClass($this->cnfg['content_entity']['content']);
                $ent->exchangeArray($entOld);
                
                $t=$this->translate;
                $ent->setName($ent->getName().' ('.$t('Copy').')');
                $form->bind($ent);
            }
            
            return new ViewModel(array(
                'form' => $form));
        }
    }

    public function editAction()
    {        
        $form = new \RtContentManager\Form\FormContent($this->cnfg, $this->entityManager);
        $form ->setHydrator(new \Zend\Stdlib\Hydrator\Reflection());
        
        //Ищем запись и, если запись не найдена
        $ent = $this->entityManager->
                getRepository($this->cnfg['content_entity']['content'])->
                find((int)$this->params()->fromRoute('id', 0));
        if ($ent==NUll)
            return $this->redirect()->toRoute('contentmanager/сontent',
                    array('lang' => $this->params()->fromRoute('lang', 'ru')));
        
        //Работаем с возвращенными из формы данными
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());            
            if ($form->isValid()) {
                $ent->exchangeArray($form->getData());
                
                $lang=$this->entityManager->
                    getRepository($this->cnfg['content_entity']['lang'])->find($ent->getLang());
                $ent->setLang($lang);
                $ContentType=$this->entityManager->
                    getRepository($this->cnfg['content_entity']['contentType'])->find($ent->getContentType());
                $ent->setContentType($ContentType);
                $ent->setDt(new \DateTime($ent->getDt()));                
                $this->entityManager->persist($ent);
                $this->entityManager->flush();
                
                return $this->redirect()->toUrl(
                        $form->get('link')->getValue());
            } else
                return new ViewModel(array(
                    'form' => $form));
        } else {
            //Наполняем форму данными при первом запуске
            $form->bind($ent);
            
            //Передаем ссылку на предыдущую страницу
            if($form->get('link')->getValue()==Null)
                $form->get('link')->setValue($this->linkPreviousPage->getLink());
            
            return new ViewModel(array(
                'form' => $form));
        }
    }

    public function deleteAction()
    {
        $ent = $this->entityManager->
                getRepository($this->cnfg['content_entity']['content'])->
                find((int)$this->params()->fromRoute('id', 0));
        //Если запись найдена
        if ($ent!=NUll) {            
            $this->entityManager->remove($ent);
            $this->entityManager->flush();
        }
        //Возвращаемся
        return $this->redirect()->toRoute('contentmanager/content',
            array('lang' => $this->params()->fromRoute('lang', 'ru')));
    }
}