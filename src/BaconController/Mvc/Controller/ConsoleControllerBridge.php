<?php
namespace BaconController\Mvc\Controller;

use UnexpectedValueException;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\Request as ConsoleRequest;
use Zend\Console\Response as ConsoleResponse;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\DispatchableInterface;
use Zend\Stdlib\Parameters;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;

class ConsoleControllerBridge implements
    DispatchableInterface,
    EventManagerAwareInterface,
    InjectApplicationEventInterface
{
    /**
     * @var ConsoleControllerInterface
     */
    protected $consoleController;

    /**
     * @var AdapterInterface
     */
    protected $console;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var MvcEvent
     */
    protected $event;

    /**
     * @param ConsoleControllerInterface $consoleController
     * @param AdapterInterface           $console
     */
    public function __construct(ConsoleControllerInterface $consoleController, AdapterInterface $console)
    {
        $this->consoleController = $consoleController;
        $this->console           = $console;
    }

    /**
     * @param  RequestInterface  $request
     * @param  ResponseInterface $response
     * @return mixed
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response = null)
    {
        $event = $this->getEvent();
        $event
            ->setRequest($request)
            ->setResponse($response ?: new ConsoleResponse())
            ->setTarget($this->consoleController);

        $result = $this->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH, $event, function ($test) {
            return ($test instanceof ResponseInterface);
        });

        if ($result->stopped()) {
            return $result->last();
        }

        return $event->getResult();
    }

    /**
     * @param  MvcEvent $event
     * @return mixed
     */
    public function onDispatch(MvcEvent $event)
    {
        $request    = $event->getRequest();
        $routeMatch = $event->getRouteMatch();

        if (!$request instanceof ConsoleRequest) {
            throw new UnexpectedValueException(sprintf(
                'Expected console request, but got %s',
                get_class($request)
            ));
        }

        if ($routeMatch === null) {
            throw new UnexpectedValueException('Expected RouteMatch, but got null');
        }

        return $this->consoleController->dispatch(new Parameters($routeMatch->getParams()), $request, $this->console);
    }

    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers([
            'Zend\Stdlib\DispatchableInterface',
            __CLASS__,
            get_class($this)
        ]);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
        $this->eventManager = $eventManager;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    /**
     * @param EventInterface $event
     */
    public function setEvent(EventInterface $event)
    {
        if (!$event instanceof MvcEvent) {
            $this->event = new MvcEvent(null, null, $event->getParams());
            return;
        }

        $this->event = $event;
    }

    /**
     * @return MvcEvent
     */
    public function getEvent()
    {
        if (!$this->event) {
            $this->setEvent(new MvcEvent());
        }

        return $this->event;
    }
}
