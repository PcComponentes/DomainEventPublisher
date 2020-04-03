<?php
declare(strict_types=1);

namespace PcComponentes\DomainEventPublisher;

use Pccomponentes\Ddd\Util\Message\Message;

class DomainEventPublisher
{
    /**
     * @var DomainEventSubscriber[]
     */
    private array $subscribers;
    private static ?DomainEventPublisher $instance = null;
    private int $id = 0;

    public static function instance(): DomainEventPublisher
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->subscribers = [];
    }

    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    public function subscribe(DomainEventSubscriber $subscriber): DomainEventSubscriber
    {
        $foundSubscriber = $this->findSubscriberByClassName(get_class($subscriber));
        if (null !== $foundSubscriber) {
            return $foundSubscriber;
        }

        $id = $this->id;
        $this->subscribers[$id] = $subscriber;
        ++$this->id;

        return $subscriber;
    }

    public function findSubscriberByClassName(string $className): ?DomainEventSubscriber
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber instanceof $className) {
                return $subscriber;
            }
        }

        return null;
    }

    public function unsubscribe($id): void
    {
        unset($this->subscribers[$id]);
    }

    public function publish(Message $domainEvent): void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($domainEvent)) {
                $subscriber->handle($domainEvent);
            }
        }
    }

    public function subscribers(): array
    {
        return $this->subscribers;
    }
}
