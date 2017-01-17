<?php

namespace RtContentManager\Factory\Controller;
use RtContentManager\Controller\GetController;
 
class GetControllerFactory
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
        $entityManager = $sl->get('Doctrine\ORM\EntityManager');
        $cnfg = $sl->get('Config');
        
        return new GetController($linkPreviousPage, $entityManager, $cnfg);
    }
}