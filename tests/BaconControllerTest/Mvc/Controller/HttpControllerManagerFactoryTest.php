<?php
namespace BaconControllerTest\Mvc\Controller;

use BaconController\Mvc\Controller\HttpControllerManagerFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;

/**
 * @coversDefaultClass BaconController\Mvc\Controller\HttpControllerManagerFactory
 * @covers ::<!public>
 */
class HttpControllerManagerFactoryTest extends TestCase
{
    /**
     * @covers ::createService
     */
    public function testCreateServiceWithoutConfig()
    {
        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('Config', []);

        $factory = new HttpControllerManagerFactory();
        $service = $factory->createService($serviceLocator);

        $this->assertInstanceOf('BaconController\Mvc\Controller\HttpControllerManager', $service);
    }

    /**
     * @covers ::createService
     */
    public function testCreateServiceWithConfig()
    {
        $controller = $this->getMock('BaconController\Mvc\Controller\HttpControllerInterface');

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('Config', [
            'http_controllers' => [
                'services' => [
                    'foo' => $controller,
                ],
            ],
        ]);

        $factory = new HttpControllerManagerFactory();
        $service = $factory->createService($serviceLocator);

        $this->assertInstanceOf('BaconController\Mvc\Controller\HttpControllerManager', $service);
        $this->assertSame($controller, $service->get('foo'));
    }
}
