<?php
namespace PcComponentes\DomainEventPublisher;

use Throwable;

class SubscriberExistException extends \Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('Subscriber already exists', 0, $previous);
    }
}
