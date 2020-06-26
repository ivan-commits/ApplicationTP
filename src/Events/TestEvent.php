<?php


namespace App\Events;


use Symfony\Contracts\EventDispatcher\Event;

class TestEvent extends Event
{
    const NAME = 'test';

    public function sayHello(){
        var_dump('ok');
    }
}