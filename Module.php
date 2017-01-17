<?php
namespace RtContentManager;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'rtdisplaytype' => function($helpers){
                    $services = $helpers->getServiceLocator();
                    return new \RtContentManager\View\Helper\RtDisplayType($services);
                },
                'rtlinktype' => function($helpers){
                    $services = $helpers->getServiceLocator();
                    return new \RtContentManager\View\Helper\RtLinkType($services);
                },
                'rtorderfield' => function($helpers){
                    $services = $helpers->getServiceLocator();
                    return new \RtContentManager\View\Helper\RtOrderField($services);
                },
                'rtcontent' => function($helpers){
                    $services = $helpers->getServiceLocator();
                    return new \RtContentManager\View\Helper\RtContent($services);
                },
                'rtcontentfirstlink' => function($helpers){
                    $services = $helpers->getServiceLocator();
                    return new \RtContentManager\View\Helper\RtContentFirstLink($services);
                },
            ),
        );
    }
}
