<?php
declare(strict_types=1);

namespace PcComponentes\DomainEventPublisher\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use PcComponentes\DomainEventPublisher\DomainEventPublisher;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use PcComponentes\Ddd\Infrastructure\Repository\EventStoreRepository;
use PcComponentes\DomainEventPublisher\Subscriber\CollectInMemoryDomainEventSubscriber;

class EventStoreMiddleware implements MiddlewareInterface
{
    private EventStoreRepository $eventStore;

    public function __construct(EventStoreRepository $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $result = $stack->next()->handle($envelope, $stack);

        $this->eventStore->add($envelope->getMessage());

        return $result;
    }
}
