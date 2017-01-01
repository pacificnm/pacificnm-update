<?php
namespace Pacificnm\Update\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Pacificnm\Update\Controller\IndexController;

class IndexControllerFactory
{

    /**
     *
     * @param ServiceLocatorInterface $serviceLocator            
     * @return \Pacificnm\Update\Controller\IndexController
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        
        $service = $realServiceLocator->get('Pacificnm\Update\Service\ServiceInterface');
        
        return new IndexController($service);
    }
}

