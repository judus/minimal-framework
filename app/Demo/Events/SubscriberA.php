<?php namespace App\Demo\Events;

use Maduser\Minimal\Event;
use Maduser\Minimal\Event\Subscriber;

class SubscriberA extends Subscriber
{
    protected $events = [
        'event.a' => 'respondToEventA',
        'event.b' => 'respondToEventB'
    ];

    public function respondToEventA($data)
    {
        show('SubscriberA responding to event.a');
        show($data);
    }

    public function respondToEventB($data)
    {
        show('SubscriberA responding to event.b');
        show($data);
    }
}