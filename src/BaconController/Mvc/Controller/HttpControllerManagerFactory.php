<?php
namespace BaconController\Mvc\Controller;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HttpControllerManagerFactory implements FactoryInterface
{
    /**
     * @return HttpControllerManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['http_controllers'])) {
            return new HttpControllerManager();
        }

        return new HttpControllerManager(new Config($config['http_controllers']));
    }
}
