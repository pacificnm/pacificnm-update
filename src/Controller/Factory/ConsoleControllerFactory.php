<?php
namespace Pacificnm\Update\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Pacificnm\Update\Controller\ConsoleController;

class ConsoleControllerFactory
{

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator            
     * @return \Pacificnm\Update\Controller\ConsoleController
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        
        $console = $realServiceLocator->get('console');
        
        $installService = $realServiceLocator->get('Pacificnm\Install\Service\ServiceInterface');
        
        $updateService = $realServiceLocator->get('Pacificnm\Update\Service\ServiceInterface');
        
        $memcached = $realServiceLocator->get('memcached');
        
        $config = $realServiceLocator->get('Config');
        
        return new ConsoleController($console, $installService, $updateService, $memcached, $config);
    }
}