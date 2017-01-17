<?php

namespace RtContentManager\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LinkPreviousPage implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $object = new \RtContentManager\Model\LinkPreviousPage($serviceLocator);        
        return $object;
    }
}