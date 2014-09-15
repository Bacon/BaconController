<?php
namespace BaconController\Mvc\Controller;

use UnexpectedValueException;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\DispatchableInterface;
use Zend\Stdlib\Parameters;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;

class HttpControllerBridge implements
    DispatchableInterface,
    EventManagerAwareInterface,
    InjectApplicationEventInterface
{
    /**
     * @var HttpControllerInterface
     */
    protected $httpController;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var MvcEvent
     */
    protected $event;

    /**
     * @param HttpControllerInterface $httpController
     */
    public function __construct(HttpControllerInterface $httpController)
    {
        $this->httpController = $httpController;
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
            ->setResponse($response ?: new HttpResponse())
            ->setTarget($this->httpController);

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

        if (!$request instanceof HttpRequest) {
            throw new UnexpectedValueException(sprintf(
                'Expected HTTP request, but got %s',
                get_class($request)
            ));
        }

        if ($routeMatch === null) {
            throw new UnexpectedValueException('Expected RouteMatch, but got null');
        }

        return $this->httpController->dispatch(new Parameters($routeMatch->getParams()), $request);
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
