<?php
declare(strict_types=1);

namespace PcComponentes\DomainEventPublisher\Subscriber;

use PcComponentes\Ddd\Util\Message\Message;
use PcComponentes\DomainEventPublisher\DomainEventSubscriber;

class CollectInMemoryDomainEventSubscriber implements DomainEventSubscriber
{
    private array $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function handle(Message $aDomainEvent): void
    {
        $this->events[] = $aDomainEvent;
    }

    public function isSubscribedTo(Message $aDomainEvent): bool
    {
        return true;
    }

    public function events(): array
    {
        return $this->events;
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }
}