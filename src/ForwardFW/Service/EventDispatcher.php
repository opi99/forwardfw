<?php

declare(strict_types=1);

namespace ForwardFW\Service;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher
    extends AbstractService
    implements EventDispatcherInterface, ListenerProviderInterface
{
    private array $listeners = [];

    public function __construct(\ForwardFW\ServiceManager $serviceManager, \ForwardFW\Config\Service\EventDispatcher $config)
    {
        parent::__construct($serviceManager, $config);

        $this->listeners = $config->getListeners();
    }

    public function dispatch(object $event): object
    {
        $listeners = $this->getListenersForEvent($event);

        foreach($listeners as $listener) {
            if ($event instanceof StoppableEventInterface
                && $event->isPropagationStopped()
            ) {
                break;
            }

            call_user_func($listener, $event);
        }

        return $event;
    }

    public function getListenersForEvent(object $event): iterable
    {
        $eventname = get_class($event);

        return $this->listeners[$eventname] ?? [];
    }
}
