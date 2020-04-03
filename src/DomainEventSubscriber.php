<?php
declare(strict_types=1);

namespace PcComponentes\DomainEventPublisher;

use Pccomponentes\Ddd\Util\Message\Message;

interface DomainEventSubscriber
{
    public function handle(Message $aDomainEvent);
    public function isSubscribedTo(Message $aDomainEvent);
    public function events(): array;
    public function clearEvents(): void;
}
