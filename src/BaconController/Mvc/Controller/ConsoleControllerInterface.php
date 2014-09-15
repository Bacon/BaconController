<?php
namespace BaconController\Mvc\Controller;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\Request;
use Zend\Stdlib\ParametersInterface;

interface ConsoleControllerInterface
{
    /**
     * @param  ParametersInterface $parameters
     * @param  Request             $request
     * @param  AdapterInterface    $console
     * @return mixed
     */
    public function dispatch(ParametersInterface $parameters, Request $request, AdapterInterface $console);
}
