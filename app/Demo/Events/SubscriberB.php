<?php

namespace App\Demo\Events;

use Maduser\Minimal\Event\Subscriber;

class SubscriberB extends Subscriber
{
    protected $events = [
        'event.b' => [
            'respondToEventB1',
            'respondToEventB2',
        ],
        'event.c' => [
            'respondToEventC',
        ]
    ];

    public function respondToEventB1()
    {
        show('SubscriberB responding to event.b (B1)');
    }

    public function respondToEventB2()
    {
        show('SubscriberB responding to event.b (B2)');
    }

    public function respondToEventC()
    {
        show('SubscriberB responding to event.c');
    }

}