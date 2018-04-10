<?php

namespace App\Demo\Events;

use Maduser\Minimal\Event\Subscriber;

class SystemSubscriber extends Subscriber
{
    protected $events = [
        'app.ready' => 'onAppReady',
        'app.routed' => 'onAppRouted',
        'app.frontController.dispatch' => 'onFrontControllerDispatch',
        'app.frontController.dispatched' => 'onFrontControllerDispatched',
        'app.respond' => 'onAppRespond',
        'app.responded' => 'onAppResponded',
        'app.terminate' => 'onAppTerminate',
        'app.terminated' => 'onAppTerminated',
    ];

    public function onAppReady()
    {
        show($this->interval() . ' - App is ready');
    }

    public function onAppRouted()
    {
        show($this->interval() . ' - App is routed');
    }

    public function onFrontControllerDispatch()
    {
        show($this->interval() . ' - App is about to dispatch the FrontController');
    }

    public function onFrontControllerDispatched()
    {
        show($this->interval() . ' - App has dispatched the FrontController');
    }

    public function onAppRespond()
    {
        show($this->interval() . ' - App is about to respond');
    }

    public function onAppResponded()
    {
        show($this->interval() . ' - App has responded');
    }

    public function onAppTerminate()
    {
        show($this->interval() . ' - App is about to terminate');
    }

    public function onAppTerminated()
    {
        show($this->interval() . ' - App is terminating');
    }

    protected function interval()
    {
        return microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
    }
}