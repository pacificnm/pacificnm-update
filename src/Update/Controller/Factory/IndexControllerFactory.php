<?php
namespace Update\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Update\Controller\IndexController;

class IndexControllerFactory
{

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator            
     * @return \Update\Controller\IndexController
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        
        $service = $realServiceLocator->get('Update\Service\ServiceInterface');
        
        return new IndexController($service);
    }
}

