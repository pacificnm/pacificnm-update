<?php
namespace Update\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Update\Controller\ConsoleController;

class ConsoleControllerFactory
{

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator            
     * @return \Update\Controller\ConsoleController
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        
        $console = $realServiceLocator->get('console');
        
        $installService = $realServiceLocator->get('Install\Service\ServiceInterface');
        
        $updateService = $realServiceLocator->get('Update\Service\ServiceInterface');
        
        $memcached = $realServiceLocator->get('memcached');
        
        $config = $realServiceLocator->get('Config');
        
        return new ConsoleController($console, $installService, $updateService, $memcached, $config);
    }
}