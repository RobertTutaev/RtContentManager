<?php

namespace RtContentManager\Factory\Controller;
use RtContentManager\Controller\ContentTypeController;
 
class ContentTypeControllerFactory
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
        
        $filterForm = $sl->get('filterform');        
        $entityManager = $sl->get('Doctrine\ORM\EntityManager');
        $classLoader = $sl->get('ClassLoader');
        $translate = $sl->get('viewhelpermanager')->get('translate');
        $cnfg = $sl->get('Config');
        
        return new ContentTypeController($filterForm, $entityManager, $classLoader, 
                $translate, $cnfg);
    }
}