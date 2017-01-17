<?php

namespace RtContentManager\Factory\Controller;
use RtContentManager\Controller\ContentController;
 
class ContentControllerFactory
{
    /**
     * @param Zend\Mvc\Controller\ControllerManager $controllerManager
     */
    
    public function __invoke($controllerManager)
    {
        /** 
         * @var Zend\ServiceManager\ServiceManager $sl 
         */        
        $sl = $controllerManager->getServiceLocator();
        
        $linkPreviousPage = $sl->get('LinkPreviousPage');
        $filterForm = $sl->get('filterform');        
        $entityManager = $sl->get('Doctrine\ORM\EntityManager');
        $classLoader = $sl->get('ClassLoader');
        $zfcAuth = $sl->get('zfcuser_auth_service');
        $translate = $sl->get('viewhelpermanager')->get('translate');
        $cnfg = $sl->get('Config');
        
        return new ContentController($linkPreviousPage, $filterForm, 
                $entityManager, $classLoader, $zfcAuth, $translate, $cnfg);
    }
}