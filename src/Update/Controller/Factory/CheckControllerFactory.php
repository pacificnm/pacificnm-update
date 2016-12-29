<?php
namespace Update\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Update\Controller\CheckController;

class CheckControllerFactory
{
    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Update\Controller\CheckController
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        
        $service = $realServiceLocator->get('Update\Service\ServiceInterface');
        
        return new CheckController($service);
    }
}

