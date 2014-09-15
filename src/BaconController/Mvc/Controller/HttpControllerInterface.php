<?php
namespace BaconController\Mvc\Controller;

use Zend\Http\Request;
use Zend\Stdlib\ParametersInterface;

interface HttpControllerInterface
{
    /**
     * @param  ParametersInterface $parameters
     * @param  Request             $request
     * @return mixed
     */
    public function dispatch(ParametersInterface $parameters, Request $request);
}
