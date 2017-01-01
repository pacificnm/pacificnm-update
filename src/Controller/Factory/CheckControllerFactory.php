<?php
namespace Pacificnm\Update\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Pacificnm\Update\Controller\CheckController;

class CheckControllerFactory
{
    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Pacificnm\Update\Controller\CheckController
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        
        $service = $realServiceLocator->get('Pacificnm\Update\Service\ServiceInterface');
        
        return new CheckController($service);
    }
}

