<?php

namespace RtContentManager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

class GetController extends AbstractActionController
{
    
    private $linkPreviousPage;
    private $entityManager;
    private $cnfg;

    public function __construct($linkPreviousPage, $entityManager, $cnfg)
    {
        $this->linkPreviousPage = $linkPreviousPage;
        $this->entityManager = $entityManager;
        $this->cnfg = $cnfg;
    }
    
    public function getaContent($idd, $lng)
    {
        $query=$this->entityManager->createQuery(
            'SELECT c FROM '.$this->cnfg['content_entity']['content'].' c
            JOIN c.lang l
            WHERE c.contentId=:idd AND l.name=:lng')->
                setParameters(array(                    
                    'idd'   => (int)$idd,
                    'lng'   => $lng,
                ));
        
        return $query->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }
    
    public function getbContent($idd, $lng)
    {
        $query=$this->entityManager->createQuery(
            'SELECT c FROM '.$this->cnfg['content_entity']['content'].' c
            JOIN c.lang l
            WHERE c.contentId=:idd AND l.name=:lng')->
                setParameters(array(                    
                    'idd'   => (int)$idd,
                    'lng'   => $lng,
                ));
        
        return $query->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }
    
    public function getcContent($idd, $lng)
    {
        $query=$this->entityManager->createQuery(
            'SELECT c FROM '.$this->cnfg['content_entity']['content'].' c
            JOIN c.lang l
            WHERE c.contentType=:idd AND l.name=:lng
            ORDER BY c.contentId')->
                setParameters(array(                    
                    'idd'   => (int)$idd,
                    'lng'   => $lng,
                ));
        
        return $query->
            setMaxResults(1)->
            setFirstResult(0)->
            getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }
    
    
    
    public function getaAction()
    {
        $request=$this->getRequest();
        $results=$request->getQuery();
        $r=Json::decode(html_entity_decode($results['result']), true);
        
        $data=$this->getaContent($r['idd'], $this->params()->fromRoute('lang', 'ru'));
        $r['v']=count($data)>0?$data[0]->getValuefl():'';
        return $this->getResponse()->setContent(Json::encode($r));
    }
    
    public function getbAction()
    {
        //1. Изменяем Layout
        $layout=$this->params()->fromRoute('layout', '');
        if($layout!='')
            $this->layout('layout/'.$layout);        
        
        //2. Формируем View
        $paginator=$this->getbContent(
            $this->params()->fromRoute('id', 0),
            $this->params()->fromRoute('lang', 'ru'));
        
        return new ViewModel(array(
            'paginator' => $paginator, 
            'link'      => $this->linkPreviousPage->getLink(),
            'noback'    => $this->params()->fromRoute('noback', 0),
            'lang'      => $this->params()->fromRoute('lang', 'ru')));
    }
    
    public function getcAction()
    {
        //1. Изменяем Layout
        $layout=$this->params()->fromRoute('layout', '');
        if($layout!='')
            $this->layout('layout/'.$layout);        
        
        //2. Формируем View
        $paginator=$this->getcContent(
            $this->params()->fromRoute('id', 0),
            $this->params()->fromRoute('lang', 'ru'));
        
        $view= new ViewModel(array(
            'paginator' => $paginator, 
            'link'      => $this->linkPreviousPage->getLink(),
            'noback'    => $this->params()->fromRoute('noback', 0),
            'lang'      => $this->params()->fromRoute('lang', 'ru')));
        
        $view->setTemplate('rt-content-manager/get/getb.phtml');
        
        return $view;
    }
}