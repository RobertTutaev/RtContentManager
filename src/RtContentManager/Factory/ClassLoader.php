<?php

namespace RtContentManager\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClassLoader implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $object = new \RtContentManager\Model\ClassLoader($serviceLocator);        
        return $object;
    }
}