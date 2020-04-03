<?php
declare(strict_types=1);

namespace PcComponentes\DomainEventPublisher\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use PcComponentes\DomainEventPublisher\DomainEventPublisher;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;
use PcComponentes\DomainEventPublisher\Subscriber\CollectInMemoryDomainEventSubscriber;

class PublisherMiddleware
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $returnValue = $stack->next()->handle($envelope, $stack);

        $events = DomainEventPublisher::instance()
            ->findSubscriberByClassName(CollectInMemoryDomainEventSubscriber::class)
            ->events();

        foreach ($events as $event) {
            $this->eventBus->dispatch(new Envelope($event), [
                new AmqpStamp($event->messageName()),
            ]);
        }

        return $returnValue;
    }
}
