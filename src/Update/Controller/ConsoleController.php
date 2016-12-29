<?php
namespace Update\Controller;

use Install\Service\ServiceInterface as InstallServiceInterface;
use RuntimeException;
use Zend\Cache\Storage\Adapter\Memcached;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Request as ConsoleRequest;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Config\Config;
use Update\Service\ServiceInterface as UpdateServiceInterface;
use Update\Entity\Entity;

class ConsoleController extends AbstractActionController
{

    protected $console;

    /**
     *
     * @var InstallServiceInterface
     */
    protected $installService;

    /**
     * 
     * @var UpdateServiceInterface
     */
    protected $updateService;
    
    /**
     *
     * @var Memcached
     */
    protected $memcached;

    /**
     *
     * @var Logger
     */
    protected $logService;

    /**
     *
     * @var Stream
     */
    protected $writerService;

    /**
     *
     * @var array
     */
    protected $config;

    /**
     * 
     * @param AdapterInterface $console
     * @param InstallServiceInterface $installService
     * @param UpdateServiceInterface $updateService
     * @param Memcached $memcached
     * @param array $config
     */
    public function __construct(AdapterInterface $console, InstallServiceInterface $installService, UpdateServiceInterface $updateService, Memcached $memcached, array $config)
    {
        $this->console = $console;
        
        $this->installService = $installService;
        
        $this->updateService = $updateService;
        
        $this->memcached = $memcached;
        
        $this->config = $config;
        
        $this->logService = new Logger();
        
        $this->writerService = new Stream('./data/log/' . date('Y-m-d') . '-update.log');
        
        $this->logService->addWriter($this->writerService);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
    public function indexAction()
    {
        // load request
        $request = $this->getRequest();
        
        // validate we are in a console
        if (! $request instanceof ConsoleRequest) {
            throw new RuntimeException('Cannot handle request of type ' . get_class($request));
        }
        
        $start = date('m/d/Y h:i a', time());
        
        $this->console->write("Start Update at {$start}\n", 3);
        
        $this->logService->info("Start Update at {$start}");
        
        $updateArray = array();
        
        // loop though each module config
        foreach ($this->config['module'] as $module) {
            // set status
            $status = "OK";
            
            // get module file
            $moduleConfigFile = getcwd() . '/module/' . $module['name'] . '/config/module.config.php';
            
            if (is_file($moduleConfigFile)) {
                $this->console->write("Working on moudule {$module['name']}\n");
                $this->logService->info("Working on moudule {$module['name']}");
                
                $moduleConfig = new Config(include $moduleConfigFile);
                
                // install module
                $moduleEntity = $this->installService->installModule($moduleConfig->module->{$module['name']}->name, $moduleConfig->module->{$module['name']}->version);
                
                // install sql files
                if ($moduleConfig->module->{$module['name']}->install) {
                    
                    // do requires first
                    if ($moduleConfig->module->{$module['name']}->install->require) {
                        
                        foreach ($moduleConfig->module->{$module['name']}->install->require as $requireModule) {
                            $tempModuleConfigFile =  getcwd() . '/module/' . $requireModule . '/config/module.config.php';
                            
                            $tempModuleConfig = new Config(include $tempModuleConfigFile);
                            
                            $requireSqlFile = getcwd() . '/module/' . $requireModule . '/' . $tempModuleConfig->module->{$requireModule}->install->sql;
                            if(is_file($requireSqlFile)) {
                                $this->installService->installTabel(file_get_contents($requireSqlFile));
                                $this->console->write("Updated SQL {$requireSqlFile}\n", 3);
                                $this->logService->info("Updated SQL {$requireSqlFile}");
                            } else {
                                $this->console->write("Failed to Update SQL {$requireSqlFile}\n", 2);
                                $this->logService->info("Failed to Update SQL {$requireSqlFile}");
                                $status = "FAIL";
                            }
                        }
                    }
                    
                    // install main sql file
                    $sqlFile = getcwd() . '/module/' . $module['name'] . '/' . $moduleConfig->module->{$module['name']}->install->sql;
                    if (is_file($sqlFile)) {
                        $this->installService->installTabel(file_get_contents($sqlFile));
                        $this->console->write("Updated SQL {$sqlFile}\n", 3);
                        $this->logService->info("Updated SQL {$sqlFile}");
                    } else {
                        $this->console->write("Failed to Update SQL {$sqlFile}\n", 2);
                        $this->logService->info("Failed to Update SQL {$sqlFile}");
                        $status = "FAIL";
                    }
                }
                
                // install ACLS
                if ($moduleConfig->acl->default) {
                    foreach ($moduleConfig->acl->default as $role => $resources) {
                        // install role
                        $roleEntity = $this->installService->installRole($role);
                        $this->console->write("Updated Role {$roleEntity->getAclRoleName()}\n", 3);
                        $this->logService->info("Updated Role {$roleEntity->getAclRoleName()}");
                        
                        // install resources
                        foreach ($resources as $resource) {
                            $resourceEntity = $this->installService->installResource($resource);
                            $this->console->write("Updated Resource {$resourceEntity->getAclResourceName()}\n", 3);
                            $this->logService->info("Updated Role {$resourceEntity->getAclResourceName()}");
                            
                            // install rule
                            $aclEntity = $this->installService->installRule($roleEntity->getAclRoleId(), $resourceEntity->getAclResourceId());
                            $this->console->write("Updated Acl Rule {$aclEntity->getAclId()}\n", 3);
                            $this->logService->info("Updated Acl Rule {$aclEntity->getAclId()}");
                        }
                    }
                } else {
                    $this->console->write("No ACLS to install skipping\n");
                    $this->logService->info("No ACLS to install skipping");
                }
                
                // install menus
                if ($moduleConfig->menu->default) {
                    foreach ($moduleConfig->menu->default as $menu) {
                        $menuEntity = $this->installService->installMenu($menu->name, $menu->route, $menu->icon, $menu->order, $menu->active, $menu->location);
                        $this->console->write("Updated Menu {$menuEntity->getMenuName()}\n", 3);
                        $this->logService->info("Updated Menu {$menuEntity->getMenuName()}");
                        
                        // install menu items
                        if ($menu->items) {
                            foreach ($menu->items as $menuItem) {
                                $menuItemEntity = $this->installService->installMenuItems($menuItem->name, $menuItem->order, $menuEntity->getMenuId(), $menuItem->route, $menuItem->icon);
                                $this->console->write("Updated Menu Item {$menuItemEntity->getMenuItemName()}\n", 3);
                                $this->logService->info("Updated Menu Item {$menuItemEntity->getMenuItemName()}");
                            }
                        } else {
                            $this->console->write("No Menu Items to install skipping\n");
                            $this->logService->info("No Menu Items to install skipping");
                        }
                    }
                } else {
                    $this->console->write("No Menu to install skipping\n");
                    $this->logService->info("No Menu to install skipping");
                }
                
                // install controllers
                if ($moduleConfig->controllers) {
                    // factories
                    if ($moduleConfig->controllers->factories) {
                        foreach ($moduleConfig->controllers->factories as $controller => $factory) {
                            $controllerArray = explode("\\", $controller);
                            $controllerEntity = $this->installService->installController($moduleEntity->getModuleId(), end($controllerArray));
                            $this->console->write("Updated Controller {$controllerEntity->getControllerName()}\n", 3);
                            $this->logService->info("Updated Controller {$controllerEntity->getControllerName()}");
                        }
                    } else {
                        $this->console->write("No Controller Factories to install skipping\n");
                        $this->logService->info("No Controller Factories to install skipping");
                    }
                    
                    // invokables
                    if ($moduleConfig->controllers->invokables) {
                        foreach ($moduleConfig->controllers->factories as $controller => $factory) {
                            $controllerArray = explode("\\", $controller);
                            $controllerEntity = $this->installService->installController($moduleEntity->getModuleId(), end($controllerArray));
                            $this->console->write("Updated Controller {$controllerEntity->getControllerName()}\n", 3);
                            $this->logService->info("Updated Controller {$controllerEntity->getControllerName()}");
                        }
                    } else {
                        $this->console->write("No Controller Invokables to install skipping\n");
                        $this->logService->info("No Controller Invokables to install skipping");
                    }
                } else {
                    $this->console->write("No Controllers to install skipping\n");
                    $this->logService->info("No Controlers to install skipping");
                }
                
                // install pages
                if ($moduleConfig->router->routes) {
                    foreach ($moduleConfig->router->routes as $key => $route) {
                        // ge$updateArrayt controller
                        if ($route->options->defaults->controller) {
                            $controllerArray = explode("\\", $route->options->defaults->controller);
                            $controllerEntity = $this->installService->installController($moduleEntity->getModuleId(), end($controllerArray));
                            $controllerId = $controllerEntity->getControllerId();
                        } else {
                            $controllerId = 0;
                        }
                        
                        // get action
                        if ($route->options->defaults->action) {
                            $action = $route->options->defaults->action;
                        } else {
                            $action = 'index';
                        }
                        
                        $pageEntity = $this->installService->installPages($key, $route['pageTitle'], $route['pageSubTitle'], $route['activeMenuItem'], $route['activeSubMenuItem'], $route['icon'], $route['layout'], $route['type'], $key, $controllerId, $moduleEntity->getModuleId(), $action);
                        $this->console->write("Updated Page {$pageEntity->getPageName()}\n", 3);
                        $this->logService->info("Updated Page {$pageEntity->getPageName()}");
                    }
                } else {
                    $this->console->write("No Routes to install skipping\n");
                    $this->logService->info("No Routes to install skipping");
                }
            } else {
                $this->console->write("No Config file skipping\n");
                $this->logService->info("No Config file skipping");
                $status ="FAIL";
            }
            
            $updateArray[] = array(
                'module' => $moduleEntity->getModuleName(),
                'version' => $moduleEntity->getModuleVersion(),
                'status' => $status
            );
            
            $entity = new Entity();
            
            $entity->setUpdateDateCheck(time());
            $entity->setUpdateStatus($status);
            $entity->setModuleId($moduleEntity->getModuleId());
            $entity->setModuleVersion($moduleEntity->getModuleVersion());
            
            $this->updateService->save($entity);
        }
        
        $table = new \Zend\Text\Table\Table(array(
            'columnWidths' => array(
                30,
                30,
                10,
            )
        ));
        
        $table->appendRow(array(
            'Module',
            'Version',
            'Status',
        ));
        
        foreach($updateArray as $update) {
            
            
            $table->appendRow(array(
                $update['module'],
                $update['version'],
                $update['status'],
            ));
        }
        
        // clear cache
        $this->memcached->flush();
        
        // done
        $end = date('m/d/Y h:i a', time());
        
        $this->console->write("Comleted Update at {$end}\n", 3);
        $this->logService->info("Comleted Update at {$end}");
        
        echo $table;
    }
}
