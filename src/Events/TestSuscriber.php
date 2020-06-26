<?php


namespace App\Events;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TestSuscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            TestEvent::NAME => 'test'
        ];
    }

     public function onTestEvent(TestEvent $event){
        var_dump($event);
     }
}