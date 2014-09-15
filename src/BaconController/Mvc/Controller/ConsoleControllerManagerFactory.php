<?php
namespace BaconController\Mvc\Controller;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConsoleControllerManagerFactory implements FactoryInterface
{
    /**
     * @return ConsoleControllerManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['console_controllers'])) {
            return new ConsoleControllerManager();
        }

        return new ConsoleControllerManager(new Config($config['console_controllers']));
    }
}
