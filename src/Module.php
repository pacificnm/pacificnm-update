<?php
namespace Pacificnm\Update;

use Zend\Console\Adapter\AdapterInterface as Console;

class Module
{
    
    
    /**
     *
     * @param Console $console
     * @return string[]|string[][]
     */
    public function getConsoleUsage(Console $console)
    {
        return array(
            'update --install' => 'installs updates',
            'update --check' => 'checks for updates'
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../config/pacificnm.update.global.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/'
                ),
            ),
        );
    }
}
