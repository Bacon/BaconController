<?php
namespace BaconController\Mvc\Controller;

use Ajasta\Core\FactoryUtils;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractHttpControllerBridgeFactory implements AbstractFactoryInterface
{
    /**
     * @var HttpControllerManager
     */
    protected $httpControllerManager;

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->getHttpControllerManager($serviceLocator)->has($requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return new HttpControllerBridge(
            $this->getHttpControllerManager($serviceLocator)->get($requestedName)
        );
    }

    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return HttpControllerMAnager
     */
    protected function getHttpControllerManager(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->httpControllerManager === null) {
            $this->httpControllerManager = FactoryUtils::resolveServiceLocator(
                $serviceLocator
            )->get('Ajasta\Core\Mvc\Controller\HttpControllerManager');
        }

        return $this->httpControllerManager;
    }
}
